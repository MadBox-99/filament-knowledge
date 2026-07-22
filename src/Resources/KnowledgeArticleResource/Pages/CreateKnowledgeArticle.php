<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource;

class CreateKnowledgeArticle extends CreateRecord
{
    protected static string $resource = KnowledgeArticleResource::class;
}
