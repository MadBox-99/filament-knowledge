<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Madbox99\FilamentSeo\Models\SeoMeta;
use Override;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $featured_image
 * @property bool $is_featured
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read KnowledgeCategory|null $category
 * @property-read SeoMeta|null $seoMeta
 */
class KnowledgeArticle extends Model
{
    protected $guarded = [];

    #[Override]
    public function getTable(): string
    {
        return config('filament-knowledge.table_names.articles', 'knowledge_articles');
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<KnowledgeCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(KnowledgeCategory::class, 'category_id');
    }

    /**
     * @return MorphOne<SeoMeta, $this>
     */
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    protected static function booted(): void
    {
        static::saving(function (KnowledgeArticle $article): void {
            if (blank($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}
