<?php

namespace App\Concerns;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait Auditable
{
    /**
     * Boot trait Auditable untuk merekam event created, updated, dan deleted secara otomatis.
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            $model->writeAuditTrail('created');
        });

        static::updated(function (Model $model) {
            $model->writeAuditTrail('updated');
        });

        static::deleted(function (Model $model) {
            $model->writeAuditTrail('deleted');
        });
    }

    /**
     * Relasi Polimorfik: Riwayat audit milik model ini.
     */
    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable')->latest();
    }

    /**
     * Label ringkas dari model untuk ditampilkan di tabel audit.
     */
    public function getAuditLabel(): string
    {
        foreach (['title', 'name', 'email', 'slug'] as $field) {
            if (! empty($this->{$field})) {
                return (string) $this->{$field};
            }
        }

        return (string) $this->getKey();
    }

    /**
     * Daftar kolom yang dikecualikan dari pencatatan audit.
     *
     * @return array<int, string>
     */
    public function getAuditExclude(): array
    {
        $defaults = ['created_at', 'updated_at', 'remember_token'];

        if (property_exists($this, 'auditExclude') && is_array($this->auditExclude)) {
            return array_unique(array_merge($defaults, $this->auditExclude));
        }

        return $defaults;
    }

    /**
     * Simpan baris audit ke tabel audits.
     */
    protected function writeAuditTrail(string $event): void
    {
        if (! Schema::hasTable('audits')) {
            return;
        }

        $excluded = $this->getAuditExclude();
        $oldValues = [];
        $newValues = [];

        if ($event === 'created') {
            $newValues = $this->sanitizeAuditAttributes($this->getAttributes(), $excluded);
        } elseif ($event === 'updated') {
            $changes = $this->getChanges();
            $filteredChanges = array_diff_key($changes, array_flip($excluded));

            // Jika yang berubah hanya kolom yang dikecualikan (misal increment views), tidak perlu dicatat
            if (empty($filteredChanges)) {
                return;
            }

            foreach ($filteredChanges as $key => $newValue) {
                $oldValues[$key] = $this->getOriginal($key);
                $newValues[$key] = $newValue;
            }

            $oldValues = $this->sanitizeAuditAttributes($oldValues, $excluded);
            $newValues = $this->sanitizeAuditAttributes($newValues, $excluded);
        } elseif ($event === 'deleted') {
            $oldValues = $this->sanitizeAuditAttributes($this->getOriginal(), $excluded);
        }

        $user = Auth::user();
        $request = app()->runningInConsole() ? null : request();

        Audit::create([
            'user_type' => $user ? get_class($user) : null,
            'user_id' => $user?->getKey(),
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'auditable_label' => $this->getAuditLabel(),
            'old_values' => ! empty($oldValues) ? $oldValues : null,
            'new_values' => ! empty($newValues) ? $newValues : null,
            'url' => $request?->fullUrl(),
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? substr((string) $request->userAgent(), 0, 1023) : null,
            'tags' => class_basename($this) . ':' . $event,
        ]);
    }

    /**
     * Bersihkan atribut sensitif atau yang dikecualikan sebelum disimpan ke JSON audit.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<int, string>  $excluded
     * @return array<string, mixed>
     */
    protected function sanitizeAuditAttributes(array $attributes, array $excluded): array
    {
        $cleaned = array_diff_key($attributes, array_flip($excluded));

        if (array_key_exists('password', $cleaned)) {
            $cleaned['password'] = '[terenkripsi]';
        }

        return $cleaned;
    }
}
