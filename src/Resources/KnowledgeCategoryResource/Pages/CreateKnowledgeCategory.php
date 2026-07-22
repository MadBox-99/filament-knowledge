<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Madbox99\FilamentKnowledge\Resources\KnowledgeCategoryResource;

class CreateKnowledgeCategory extends CreateRecord
{
    protected static string $resource = KnowledgeCategoryResource::class;
}
