<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $slug = 'notifications';

    protected static ?int $navigationSort = 1;

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

    public static function getNavigationLabel(): string
    {
        return __('messages.notifications');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Notification::where('is_read', false)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = Notification::where('is_read', false)->count();
        return $count > 0 ? 'danger' : 'success';
    }

    public static function getPluralLabel(): string { return __('messages.notifications'); }
    public static function getLabel(): string { return __('messages.notification'); }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('messages.notification'))
                    ->schema([
                        Forms\Components\Tabs::make('Language')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->icon('heroicon-o-language')
                                    ->badge('EN')
                                    ->schema([
                                        Forms\Components\TextInput::make('title_en')
                                            ->label('Title (English)')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('body_en')
                                            ->label('Body (English)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Tabs\Tab::make('کوردی')
                                    ->icon('heroicon-o-language')
                                    ->badge('CKB')
                                    ->schema([
                                        Forms\Components\TextInput::make('title_ckb')
                                            ->label('ناونیشان (کوردی)')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('body_ckb')
                                            ->label('ناوەرۆک (کوردی)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'custom' => 'Custom',
                                'article' => 'Article',
                                'journalist' => 'Journalist',
                                'category' => 'Category',
                                'ad' => 'Ad',
                                'comment' => 'Comment',
                            ])
                            ->default('custom')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.notification'))
                    ->searchable()
                    ->sortable()
                    ->weight(fn (Notification $record): string => $record->is_read ? 'normal' : 'bold')
                    ->color(fn (Notification $record): ?string => $record->is_read ? 'gray' : null),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'article',
                        'warning' => 'journalist',
                        'success' => 'category',
                        'info' => 'ad',
                        'danger' => 'comment',
                        'secondary' => 'custom',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'info' => 'published',
                        'warning' => 'updated',
                        'primary' => 'broadcast',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('body')
                    ->label(__('messages.details'))
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('messages.by'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label(__('messages.read'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->label(__('messages.when')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'article' => 'Article',
                        'journalist' => 'Journalist',
                        'category' => 'Category',
                        'ad' => 'Ad',
                        'comment' => 'Comment',
                        'custom' => 'Custom',
                    ])
                    ->native(false),
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'created' => 'Created',
                        'published' => 'Published',
                        'updated' => 'Updated',
                        'broadcast' => 'Broadcast',
                    ])
                    ->native(false),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label(__('messages.read_status'))
                    ->trueLabel(__('messages.read'))
                    ->falseLabel(__('messages.mark_as_unread'))
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_read')
                    ->label(__('messages.mark_as_read'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Notification $record): bool => !$record->is_read)
                    ->action(fn (Notification $record) => $record->update(['is_read' => true])),
                Tables\Actions\Action::make('mark_unread')
                    ->label(__('messages.mark_as_unread'))
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (Notification $record): bool => $record->is_read)
                    ->action(fn (Notification $record) => $record->update(['is_read' => false])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_all_read')
                        ->label(__('messages.mark_selected_read'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_read' => true])),
                    Tables\Actions\BulkAction::make('mark_all_unread')
                        ->label(__('messages.mark_selected_unread'))
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_read' => false])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('30s');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user'); // skip 'notifiable' to avoid orphaned morph errors
    }
}
