<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Engagement';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'author']);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Comment::where('is_approved', false)->count();
    }

    public static function getPluralLabel(): string
    {
        return __('messages.comments');
    }

    public static function getLabel(): string
    {
        return __('messages.comment');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('messages.comment_section'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('author_name')
                                    ->disabled()
                                    ->label(__('messages.author')),
                                Forms\Components\TextInput::make('author_email')
                                    ->email()
                                    ->disabled()
                                    ->label(__('messages.email')),
                            ]),
                        Forms\Components\Select::make('article_id')
                            ->relationship('article', 'title')
                            ->disabled()
                            ->label(__('messages.article'))
                            ->searchable(),
                        Forms\Components\Textarea::make('body')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_approved')
                            ->label(__('messages.approved'))
                            ->required()
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('article.title')
                    ->label(__('messages.article'))
                    ->searchable()
                    ->limit(40)
                    ->url(fn (Comment $record): string => route('filament.admin.resources.articles.edit', $record->article_id)),
                Tables\Columns\TextColumn::make('body')
                    ->limit(80)
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_approved')
                    ->label(__('messages.status'))
                    ->badge()
                    ->formatStateUsing(fn (Comment $record): string => $record->is_approved ? __('messages.approved') : __('messages.pending'))
                    ->color(fn (Comment $record): string => $record->is_approved ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->label(__('messages.submitted')),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label(__('messages.approval_status'))
                    ->trueLabel(__('messages.approved'))
                    ->falseLabel(__('messages.unapprove'))
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label(__('messages.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Comment $record): bool => !$record->is_approved)
                    ->action(function (Comment $record) {
                        $record->update(['is_approved' => true]);
                    }),
                Tables\Actions\Action::make('unapprove')
                    ->label(__('messages.unapprove'))
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (Comment $record): bool => $record->is_approved)
                    ->action(function (Comment $record) {
                        $record->update(['is_approved' => false]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approveAll')
                        ->label(__('messages.approve_selected'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),
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
            'index' => Pages\ListComments::route('/'),
        ];
    }
}
