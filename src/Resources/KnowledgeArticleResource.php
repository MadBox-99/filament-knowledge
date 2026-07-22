<?php

declare(strict_types=1);

namespace Madbox99\FilamentKnowledge\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Madbox99\FilamentKnowledge\Concerns\ResolvesPluginNavigation;
use Madbox99\FilamentKnowledge\Models\KnowledgeArticle;
use Madbox99\FilamentKnowledge\Resources\KnowledgeArticleResource\Pages;
use Madbox99\FilamentSeo\Forms\SeoFields;
use Override;
use UnitEnum;

class KnowledgeArticleResource extends Resource
{
    use ResolvesPluginNavigation;

    protected static ?string $model = KnowledgeArticle::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|UnitEnum|null $navigationGroup = 'Knowledge base';

    protected static ?string $navigationLabel = 'Articles';

    protected static ?string $modelLabel = 'Article';

    protected static ?string $pluralModelLabel = 'Articles';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Content')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Excerpt')
                                    ->rows(3)
                                    ->maxLength(500),
                                Forms\Components\RichEditor::make('content')
                                    ->label('Content')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                        ...static::seoFields(),
                    ])
                    ->columnSpan(['lg' => 2]),
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Publishing')
                            ->schema([
                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(false),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Name')
                                            ->required(),
                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug'),
                                    ]),
                            ]),
                        Schemas\Components\Section::make('Image')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->image()
                                    ->directory(config('filament-knowledge.upload_directory', 'knowledge-articles'))
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    /** @return array<Schemas\Components\Component> */
    protected static function seoFields(): array
    {
        if (! class_exists(SeoFields::class)) {
            return [];
        }

        return [
            SeoFields::make()
                ->schema([
                    ...SeoFields::schema(),
                    Forms\Components\Select::make('og_type')
                        ->label('OG Type')
                        ->options([
                            'article' => 'article',
                            'website' => 'website',
                        ])
                        ->default('article'),
                    Forms\Components\Textarea::make('schema_markup')
                        ->label('Custom JSON-LD (schema.org)')
                        ->rows(5)
                        ->helperText('Optional, valid JSON. Rendered in addition to the automatic structured data.')
                        ->rules(['nullable', 'json'])
                        ->formatStateUsing(fn ($state): ?string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $state)
                        ->dehydrateStateUsing(fn (?string $state) => filled($state) ? json_decode($state, true) : null),
                ]),
        ];
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->dateTime('Y-m-d')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKnowledgeArticles::route('/'),
            'create' => Pages\CreateKnowledgeArticle::route('/create'),
            'edit' => Pages\EditKnowledgeArticle::route('/{record}/edit'),
        ];
    }
}
