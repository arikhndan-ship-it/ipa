<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function canDelete($record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function canDeleteAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Article::where('status', 'draft')->count();
    }

    public static function getPluralLabel(): string
    {
        return __('messages.articles');
    }

    public static function getLabel(): string
    {
        return __('messages.article');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('messages.Article'))
                    ->tabs([

                        // === ENGLISH TAB ===
                        Forms\Components\Tabs\Tab::make(__('messages.article_tab_en'))
                            ->icon('heroicon-o-language')
                            ->badge('EN')
                            ->schema([
                                Forms\Components\TextInput::make('translation_en_title')
                                    ->label(__('messages.form_title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', str($state)->slug());
                                    }),
                                Forms\Components\RichEditor::make('translation_en_body')
                                    ->label(__('messages.form_body'))
                                    ->required()
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('attachments'),
                                Forms\Components\Textarea::make('translation_en_excerpt')
                                    ->label(__('messages.form_excerpt'))
                                    ->columnSpanFull(),
                            ]),

                        // === KURDISH TAB ===
                        Forms\Components\Tabs\Tab::make(__('messages.article_tab_ckb'))
                            ->icon('heroicon-o-language')
                            ->badge('CKB')
                            ->schema([
                                Forms\Components\TextInput::make('translation_ckb_title')
                                    ->label(__('messages.form_ckb_title'))
                                    ->maxLength(255),
                                Forms\Components\RichEditor::make('translation_ckb_body')
                                    ->label(__('messages.form_ckb_body'))
                                    ->columnSpanFull()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('attachments'),
                                Forms\Components\Textarea::make('translation_ckb_excerpt')
                                    ->label(__('messages.form_ckb_excerpt'))
                                    ->columnSpanFull(),
                            ]),

                        // === DETAILS TAB ===
                        Forms\Components\Tabs\Tab::make(__('messages.article_tab_details'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make(__('messages.section_publication'))
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('slug')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(Article::class, 'slug', ignoreRecord: true)
                                                    ->helperText(__('messages.auto_filled_from_en')),
                                                Forms\Components\Select::make('author_id')
                                                    ->label(__('messages.author'))
                                                    ->relationship('author', 'name')
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(fn () => auth()->id()),
                                                Forms\Components\Select::make('status')
                                                    ->options([
                                                        'draft' => __('messages.draft'),
                                                        'published' => __('messages.published'),
                                                        'archived' => __('messages.archived'),
                                                    ])
                                                    ->required()
                                                    ->default('draft')
                                                    ->native(false),
                                                Forms\Components\DateTimePicker::make('published_at')
                                                    ->label(__('messages.published_at'))
                                                    ->helperText(__('messages.auto_set_when_publishing')),
                                            ]),
                                    ]),

                                Forms\Components\Section::make(__('messages.section_flags'))
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\Toggle::make('is_featured')
                                                    ->label(__('messages.featured_article'))
                                                    ->inline(false),
                                                Forms\Components\Toggle::make('is_breaking')
                                                    ->label(__('messages.breaking_news'))
                                                    ->inline(false),
                                            ]),
                                    ]),

                                Forms\Components\Section::make(__('messages.section_categories'))
                                    ->schema([
                                        Forms\Components\Select::make('categories')
                                            ->relationship('categories', 'id')
                                            ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->name)
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make(__('messages.section_featured_image'))
                                    ->schema([
                                        Forms\Components\FileUpload::make('og_image')
                                            ->label(__('messages.featured_image'))
                                            ->image()
                                            ->imageEditor()
                                            ->directory('articles/featured')
                                            ->disk('public')
                                            ->visibility('public')
                                            ->columnSpanFull()
                                            ->helperText(__('messages.upload_featured_image')),
                                    ]),
                            ]),

                        // === SEO TAB ===
                        Forms\Components\Tabs\Tab::make(__('messages.article_tab_seo'))
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Forms\Components\Section::make(__('messages.seo_section'))
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label(__('messages.meta_title'))
                                            ->maxLength(255)
                                            ->helperText(__('messages.auto_filled_from_en')),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label(__('messages.meta_description'))
                                            ->maxLength(65535)
                                            ->helperText(__('messages.auto_filled_from_en')),
                        Forms\Components\TextInput::make('og_image_url')
                            ->label(__('messages.og_image_url'))
                            ->maxLength(255)
                            ->url()
                            ->helperText(__('messages.upload_featured_image')),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('og_image')
                    ->label(__('messages.image'))
                    ->toggleable()
                    ->circular()
                    ->size(50)
                    ->disk('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.form_title'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->orWhereHas('translations', function ($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        });
                    })
                    ->limit(40)
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('messages.author'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('categories.name')
                    ->label(__('messages.categories'))
                    ->badge()
                    ->separator(', ')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'secondary' => 'archived',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-check-circle' => 'published',
                        'heroicon-o-archive-box' => 'archived',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label(__('messages.featured'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_breaking')
                    ->boolean()
                    ->label(__('messages.breaking'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->sortable()
                    ->label(__('messages.views'))
                    ->numeric()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => __('messages.draft'),
                        'published' => __('messages.published'),
                        'archived' => __('messages.archived'),
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('categories', 'slug')
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\Category $record) => $record->name)
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('messages.featured')),
                Tables\Filters\TernaryFilter::make('is_breaking')
                    ->label(__('messages.breaking_news')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('publish')
                        ->label(__('messages.publish'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Article $record): bool => $record->status !== 'published')
                        ->action(function (Article $record) {
                            $record->update([
                                'status' => 'published',
                                'published_at' => $record->published_at ?? now(),
                            ]);
                        }),
                    Tables\Actions\Action::make('draft')
                        ->label(__('messages.move_to_draft'))
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->visible(fn (Article $record): bool => $record->status === 'published')
                        ->action(fn (Article $record) => $record->update(['status' => 'draft'])),
                    Tables\Actions\Action::make('toggle_featured')
                        ->label(fn (Article $record) => $record->is_featured ? __('messages.unfeature') : __('messages.feature'))
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(function (Article $record) {
                            $record->update(['is_featured' => !$record->is_featured]);
                        }),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
