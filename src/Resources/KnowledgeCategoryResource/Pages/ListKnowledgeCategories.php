<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource;
use Override;

class ListKnowledgeCategories extends ListRecords
{
    protected static string $resource = KnowledgeCategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
