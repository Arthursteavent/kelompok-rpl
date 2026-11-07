<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
    ];

    /**
     * Relasi One-to-Many: Satu Kategori memiliki banyak Artikel.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Kelas badge Tailwind berdasarkan nilai kolom color.
     */
    public function tailwindBadgeClass(): string
    {
        return match ($this->color) {
            'primary' => 'bg-blue-600 text-white',
            'success' => 'bg-emerald-600 text-white',
            'danger' => 'bg-rose-600 text-white',
            'warning' => 'bg-amber-500 text-slate-950',
            'info' => 'bg-sky-500 text-white',
            'dark' => 'bg-slate-800 text-white dark:bg-slate-700',
            default => 'bg-slate-600 text-white',
        };
    }
}

