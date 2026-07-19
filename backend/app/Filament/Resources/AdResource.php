<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Advertising';

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
        return __('messages.ads');
    }

    public static function getLabel(): string
    {
        return __('messages.ad');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('messages.ad'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('messages.content_tab'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Select::make('type')
                                            ->options([
                                                'banner' => __('messages.banner'),
                                                'sidebar' => __('messages.sidebar'),
                                                'in-article' => __('messages.in_article'),
                                            ])
                                            ->required()
                                            ->native(false),
                                        Forms\Components\FileUpload::make('image_path')
                                            ->label(__('messages.image'))
                                            ->image()
                                            ->directory('ads')
                                            ->maxSize(2048)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('link_url')
                                            ->label(__('messages.link_url'))
                                            ->url()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('messages.settings_tab'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label(__('messages.active'))
                                            ->default(true),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->numeric()
                                            ->default(0)
                                            ->label(__('messages.sort_order')),
                                        Forms\Components\DatePicker::make('start_date')
                                            ->label(__('messages.start_date')),
                                        Forms\Components\DatePicker::make('end_date')
                                            ->label(__('messages.end_date')),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40)
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'banner',
                        'warning' => 'sidebar',
                        'info' => 'in-article',
                    ])
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label(__('messages.image'))
                    ->circular()
                    ->defaultImageUrl(url('/images/logo-en.png'))
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('messages.active'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label(__('messages.sort_order'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('M j, Y')
                    ->label(__('messages.start'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date('M j, Y')
                    ->label(__('messages.end'))
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'banner' => __('messages.banner'),
                        'sidebar' => __('messages.sidebar'),
                        'in-article' => __('messages.in_article'),
                    ])
                    ->native(false),
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
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
