<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotifications extends ListRecords
{
    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Send Notification')
                ->icon('heroicon-o-bell-alert')
                ->color('primary')
                ->modalWidth('lg')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['action'] = 'broadcast';
                    $data['user_id'] = auth()->id();
                    $data['is_read'] = false;
                    $data['notifiable_type'] = null;
                    $data['notifiable_id'] = null;
                    $data['title'] = $data['title_en'] ?? $data['title_ckb'] ?? '';
                    $data['body'] = $data['body_en'] ?? $data['body_ckb'] ?? $data['title'];
                    return $data;
                })
                ->after(function (\App\Models\Notification $record) {
                    try {
                        \App\Services\PushNotificationService::sendToAllDevices(
                            titleEn: $record->title_en ?? $record->title,
                            titleCkb: $record->title_ckb ?? $record->title,
                            bodyEn: $record->body_en ?? $record->body,
                            bodyCkb: $record->body_ckb ?? $record->body,
                            data: ['type' => $record->type, 'action' => 'broadcast'],
                        );
                    } catch (\Exception $e) {
                        logger()->error('Push notification failed: ' . $e->getMessage());
                    }
                }),
            Actions\Action::make('mark_all')
                ->label('Mark All as Read')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    \App\Models\Notification::where('is_read', false)
                        ->update(['is_read' => true]);
                    \Filament\Notifications\Notification::make()
                        ->title('All notifications marked as read')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('clear')
                ->label('Clear All')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \App\Models\Notification::truncate();
                    \Filament\Notifications\Notification::make()
                        ->title('All notifications cleared')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getTitle(): string
    {
        return __('messages.notifications');
    }
}
