<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource;
use Override;

class EditKnowledgeArticle extends EditRecord
{
    protected static string $resource = KnowledgeArticleResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
