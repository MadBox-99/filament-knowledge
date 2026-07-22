<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource;
use Override;

class ListKnowledgeArticles extends ListRecords
{
    protected static string $resource = KnowledgeArticleResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
