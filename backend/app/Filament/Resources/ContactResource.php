<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Engagement';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Contact::where('is_read', false)->count();
    }

    public static function getPluralLabel(): string
    {
        return __('messages.tips_reports');
    }

    public static function getLabel(): string
    {
        return __('messages.tip_report');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('messages.contact_information'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->disabled()
                                    ->label(__('messages.name')),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->disabled()
                                    ->label(__('messages.email')),
                            ]),
                        Forms\Components\TextInput::make('subject')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('message')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(6),
                        Forms\Components\TextInput::make('locale')
                            ->disabled()
                            ->label(__('messages.language')),
                        Forms\Components\Toggle::make('is_read')
                            ->label(__('messages.mark_as_read'))
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('message')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\TextColumn::make('locale')
                    ->label(__('messages.lang'))
                    ->badge()
                    ->getStateUsing(fn (Contact $record): string => $record->locale === 'ckb' ? 'کوردی' : 'English')
                    ->colors([
                        'danger' => 'English',
                        'primary' => 'کوردی',
                    ]),
                Tables\Columns\IconColumn::make('is_read')
                    ->label(__('messages.read'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->label(__('messages.submitted')),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label(__('messages.read_status'))
                    ->trueLabel(__('messages.read'))
                    ->falseLabel('Unread')
                    ->nullable(),
                Tables\Filters\SelectFilter::make('locale')
                    ->label(__('messages.language'))
                    ->options([
                        'ckb' => 'کوردی',
                        'en' => 'English',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markRead')
                    ->label(__('messages.mark_as_read'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Contact $record): bool => !$record->is_read)
                    ->action(function (Contact $record) {
                        $record->update(['is_read' => true]);
                    }),
                Tables\Actions\Action::make('markUnread')
                    ->label(__('messages.mark_as_unread'))
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (Contact $record): bool => $record->is_read)
                    ->action(function (Contact $record) {
                        $record->update(['is_read' => false]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('markAllRead')
                        ->label(__('messages.mark_selected_read'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_read' => true])),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}
