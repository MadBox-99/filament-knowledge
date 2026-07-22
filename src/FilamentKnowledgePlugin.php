<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource;
use Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource;

class FilamentKnowledgePlugin implements Plugin
{
    protected ?string $navigationGroup = 'Knowledge base';

    protected ?int $navigationSort = 1;

    public function getId(): string
    {
        return 'filament-knowledge';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function navigationSort(?int $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            KnowledgeArticleResource::class,
            KnowledgeCategoryResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
