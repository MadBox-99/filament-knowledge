<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Override;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, KnowledgeArticle> $articles
 * @property-read int|null $articles_count
 */
class KnowledgeCategory extends Model
{
    protected $guarded = [];

    #[Override]
    public function getTable(): string
    {
        return config('filament-knowledge.table_names.categories', 'knowledge_categories');
    }

    /**
     * @return HasMany<KnowledgeArticle, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(KnowledgeArticle::class, 'category_id');
    }

    protected static function booted(): void
    {
        static::saving(function (KnowledgeCategory $category): void {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
