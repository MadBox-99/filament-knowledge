<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource;
use Override;

class EditKnowledgeCategory extends EditRecord
{
    protected static string $resource = KnowledgeCategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
