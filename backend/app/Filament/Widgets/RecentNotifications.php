<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentNotifications extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        $unread = Notification::where('is_read', false)->count();
        $heading = __('messages.notifications');
        return $unread > 0 ? "{$heading} ({$unread})" : $heading;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Notification::query()
                    ->with('user')
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('messages.notification'))
                    ->weight(fn (Notification $record): string => $record->is_read ? 'normal' : 'bold')
                    ->limit(40),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'article',
                        'warning' => 'journalist',
                        'success' => 'category',
                        'info' => 'ad',
                        'danger' => 'comment',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('messages.by')),
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label(__('messages.read')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->label(__('messages.when')),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('messages.view_all'))
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn (): string => route('filament.admin.resources.notifications.index')),
            ])
            ->paginated(false)
            ->striped();
    }
}
