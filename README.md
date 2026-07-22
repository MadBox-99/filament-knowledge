# Filament Knowledge Base

Knowledge base plugin for [Filament](https://filamentphp.com) v5 — articles and categories, with optional SEO meta integration.

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Filament v5

## Installation

```bash
composer require madbox-99/filament-knowledge
php artisan migrate
```

The package registers its own migrations, so no publishing step is needed. To customise the table names, publish the config file:

```bash
php artisan vendor:publish --tag=filament-knowledge-config
```

## Registering the plugin

Add the plugin to your panel provider:

```php
use Madbox99\FilamentKnowledge\FilamentKnowledgePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(FilamentKnowledgePlugin::make());
}
```

Both resources read their navigation group from the plugin instance, so you can move them into an existing group of your own application:

```php
->plugin(
    FilamentKnowledgePlugin::make()
        ->navigationGroup('Content')
        ->navigationSort(3)
)
```

## What you get

| Resource | Purpose |
|---|---|
| `KnowledgeArticleResource` | Articles: title, slug, excerpt, rich text content, featured image, category, publish and feature toggles, publish date, sort order |
| `KnowledgeCategoryResource` | Categories: name, slug, sort order, article count |

Slugs are generated from the title (or name) whenever they are left blank.

## Models

`Madbox99\FilamentKnowledge\Models\KnowledgeArticle` and `KnowledgeCategory`. Both ship `@property` annotations, so PHPStan and Larastan can type attribute access in the consuming application.

```php
use Madbox99\FilamentKnowledge\Models\KnowledgeArticle;

$articles = KnowledgeArticle::published()
    ->with('category')
    ->orderBy('sort_order')
    ->orderByDesc('published_at')
    ->paginate(9);

$featured = KnowledgeArticle::featured()->published()->get();
```

Available scopes: `published()`, `featured()`.

## SEO integration

If [`madbox-99/filament-seo`](https://github.com/MadBox-99/filament-seo) is installed, the article form gains a collapsible SEO section (title, description, keywords, canonical URL, noindex/nofollow, OG type and custom JSON-LD), and articles expose a `seoMeta` relationship:

```php
$article->seoMeta?->description;
```

Without that package the SEO section is simply omitted — it is a `suggest`, not a hard dependency.

## Frontend

This package covers the admin side only. The public pages are left to your application, so they can use its own layout, routing and design system.

## Configuration

```php
return [
    'table_names' => [
        'articles' => 'knowledge_articles',
        'categories' => 'knowledge_categories',
    ],

    'upload_directory' => 'knowledge-articles',
];
```

## License

MIT. See [LICENSE](LICENSE).
