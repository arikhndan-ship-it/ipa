<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Content';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canDelete($record): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function canDeleteAny(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function getPluralLabel(): string
    {
        return __('messages.categories');
    }

    public static function getLabel(): string
    {
        return __('messages.category');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('messages.category'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->icon('heroicon-o-language')
                            ->badge('EN')
                            ->schema([
                                Forms\Components\TextInput::make('translation_en_name')
                                    ->label(__('messages.form_name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', str($state)->slug());
                                    }),
                                Forms\Components\Textarea::make('translation_en_description')
                                    ->label(__('messages.form_description'))
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('کوردی')
                            ->icon('heroicon-o-language')
                            ->badge('CKB')
                            ->schema([
                                Forms\Components\TextInput::make('translation_ckb_name')
                                    ->label(__('messages.form_ckb_category_name'))
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('translation_ckb_description')
                                    ->label(__('messages.form_ckb_category_desc'))
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('messages.settings'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Category::class, 'slug', ignoreRecord: true)
                                            ->helperText('Auto-filled from English name'),
                                        Forms\Components\Select::make('parent_id')
                                            ->label(__('messages.parent_category'))
                                            ->relationship('parent', 'slug')
                                            ->getOptionLabelFromRecordUsing(fn (\App\Models\Category $record) => $record->name)
                                            ->searchable()
                                            ->preload()
                                            ->nullable(),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->numeric()
                                            ->default(0),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label(__('messages.active'))
                                            ->default(true),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.name'))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->orWhereHas('translations', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->limit(50)
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('messages.slug_copied')),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(__('messages.parent_category'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('articles_count')
                    ->counts('articles')
                    ->label(__('messages.articles'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('messages.active'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label(__('messages.sort_order'))
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('messages.active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->striped();
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
