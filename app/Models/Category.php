<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

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
}

