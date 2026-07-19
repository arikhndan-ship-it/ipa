<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalistResource\Pages;
use App\Models\Journalist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JournalistResource extends Resource
{
    protected static ?string $model = Journalist::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
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

    public static function getPluralLabel(): string { return __('messages.journalists'); }
    public static function getLabel(): string { return __('messages.journalist'); }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('messages.journalist'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->icon('heroicon-o-language')->badge('EN')
                            ->schema([
                                Forms\Components\TextInput::make('translation_en_name')
                                    ->label(__('messages.form_name'))->required()->maxLength(255),
                                Forms\Components\Textarea::make('translation_en_bio')
                                    ->label(__('messages.bio_about'))->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('کوردی')
                            ->icon('heroicon-o-language')->badge('CKB')
                            ->schema([
                                Forms\Components\TextInput::make('translation_ckb_name')
                                    ->label(__('messages.form_ckb_journalist_name'))->maxLength(255),
                                Forms\Components\Textarea::make('translation_ckb_bio')
                                    ->label(__('messages.form_ckb_journalist_bio'))->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('messages.settings'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\FileUpload::make('image')
                                        ->label(__('messages.photo'))
                                        ->image()
                                        ->directory('journalists')
                                        ->visibility('public'),
                                    Forms\Components\TextInput::make('sort_order')
                                        ->numeric()->default(0),
                                    Forms\Components\Toggle::make('is_active')
                                        ->label(__('messages.active'))->default(true),
                                ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label(__('messages.photo'))->circular()->size(50),
                Tables\Columns\TextColumn::make('name')->label(__('messages.name'))
                    ->searchable(query: fn(Builder $query, string $search) =>
                        $query->orWhereHas('translations', fn($q) => $q->where('name', 'like', "%{$search}%")))
                    ->limit(50)->sortable(),
                Tables\Columns\TextColumn::make('bio')->label(__('messages.bio'))->limit(60),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label(__('messages.active'))->sortable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->label(__('messages.sort_order')),
            ])
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label(__('messages.active'))])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->defaultSort('sort_order')->striped();
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalists::route('/'),
            'create' => Pages\CreateJournalist::route('/create'),
            'edit' => Pages\EditJournalist::route('/{record}/edit'),
        ];
    }
}
