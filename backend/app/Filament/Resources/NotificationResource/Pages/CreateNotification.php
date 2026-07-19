<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use App\Models\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['action'] = 'broadcast';
        $data['user_id'] = auth()->id();
        $data['is_read'] = false;
        $data['notifiable_type'] = null;
        $data['notifiable_id'] = null;
        // Copy bilingual fields to the main title/body for display in table
        $data['title'] = $data['title_en'] ?? $data['title_ckb'] ?? '';
        $data['body'] = $data['body_en'] ?? $data['body_ckb'] ?? $data['title'];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Notification sent successfully!';
    }

    protected function afterCreate(): void
    {
        // Send push notification to all registered devices
        try {
            \App\Services\PushNotificationService::sendToAllDevices(
                titleEn: $this->record->title_en ?? $this->record->title,
                titleCkb: $this->record->title_ckb ?? $this->record->title,
                bodyEn: $this->record->body_en ?? $this->record->body,
                bodyCkb: $this->record->body_ckb ?? $this->record->body,
                data: ['type' => $this->record->type, 'action' => 'broadcast'],
            );
        } catch (\Exception $e) {
            logger()->error('Push notification failed after create: ' . $e->getMessage());
        }
    }
}
