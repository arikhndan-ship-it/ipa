<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $slug = 'settings';

    public static function getNavigationLabel(): string
    {
        return __('messages.settings');
    }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['author']);
    }

    public static function getPluralLabel(): string
    {
        return __('messages.settings');
    }

    public static function getLabel(): string
    {
        return __('messages.setting');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('messages.setting_details'))
                    ->description(__('messages.configure_site_settings'))
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->maxLength(255)
                            ->unique(Setting::class, 'key', ignoreRecord: true)
                            ->disabled(fn (?Setting $record): bool => $record !== null)
                            ->helperText(__('messages.unique_key_identifier')),
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => __('messages.text'),
                                'textarea' => __('messages.textarea'),
                                'json' => __('messages.json'),
                                'number' => __('messages.number'),
                                'boolean' => __('messages.boolean'),
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('value')
                            ->label(__('messages.value'))
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage(__('messages.key_copied'))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'text',
                        'warning' => 'textarea',
                        'danger' => 'json',
                        'success' => 'number',
                        'info' => 'boolean',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('messages.value'))
                    ->limit(60)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => __('messages.text'),
                        'textarea' => __('messages.textarea'),
                        'json' => __('messages.json'),
                        'number' => __('messages.number'),
                        'boolean' => __('messages.boolean'),
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
