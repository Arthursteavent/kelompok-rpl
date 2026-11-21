<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Audit extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'auditable_label',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    /**
     * User pelaku perubahan/action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Entitas model yang diaudit (Article, Category, User, dll).
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Catat audit trail manual untuk action tertentu (misal: login, logout, register, login_failed).
     */
    public static function logAction(
        string $event,
        ?Model $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $tags = null,
        ?string $label = null,
        ?User $actor = null
    ): self {
        $user = $actor ?? Auth::user();
        $request = app()->runningInConsole() ? null : request();

        return self::create([
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user?->getKey(),
            'event' => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable?->getKey(),
            'auditable_label' => $label ?? ($auditable && method_exists($auditable, 'getAuditLabel') ? $auditable->getAuditLabel() : null),
            'old_values' => ! empty($oldValues) ? $oldValues : null,
            'new_values' => ! empty($newValues) ? $newValues : null,
            'url' => $request?->fullUrl(),
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 1023) : null,
            'tags' => $tags,
        ]);
    }

    /**
     * Nama modul/entitas yang ramah dibaca.
     */
    public function modelLabel(): string
    {
        return match ($this->auditable_type) {
            Article::class => 'Artikel',
            Category::class => 'Kategori',
            User::class => 'User',
            default => 'Autentikasi / Sistem',
        };
    }

    /**
     * Label event audit dalam Bahasa Indonesia.
     */
    public function eventLabel(): string
    {
        return match ($this->event) {
            'created' => 'Tambah Data (Created)',
            'updated' => 'Ubah Data (Updated)',
            'deleted' => 'Hapus Data (Deleted)',
            'login' => 'Login Berhasil',
            'logout' => 'Logout',
            'register' => 'Registrasi Akun',
            'login_failed' => 'Login Gagal',
            default => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }

    /**
     * Kelas warna badge Tailwind sesuai jenis event.
     */
    public function eventBadgeClass(): string
    {
        return match ($this->event) {
            'created', 'register' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            'updated' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
            'deleted', 'login_failed' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/50 dark:text-red-300 dark:border-red-800',
            'login', 'logout' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
            default => 'bg-zinc-100 text-zinc-700 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:border-zinc-700',
        };
    }
}
