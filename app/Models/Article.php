<?php

namespace App\Models;

use App\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use Auditable, HasFactory, HasUuids;

    /**
     * Abaikan kenaikan counter views publik agar tidak memenuhi log audit.
     *
     * @var array<int, string>
     */
    protected array $auditExclude = [
        'views',
    ];

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'excerpt',
        'content',
        'image_url',
        'is_published',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'views' => 'integer',
        ];
    }

    /**
     * Relasi Many-to-One (Inverse One-to-Many): Setiap Artikel dimiliki oleh satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

