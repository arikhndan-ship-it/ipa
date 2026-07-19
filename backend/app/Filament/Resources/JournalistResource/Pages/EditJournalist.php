<?php

namespace App\Filament\Resources\JournalistResource\Pages;

use App\Filament\Resources\JournalistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJournalist extends EditRecord
{
    protected static string $resource = JournalistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        $en = $record->translations->firstWhere('locale', 'en');
        $data['translation_en_name'] = $en?->name ?? '';
        $data['translation_en_bio'] = $en?->bio ?? '';

        $ckb = $record->translations->firstWhere('locale', 'ckb');
        $data['translation_ckb_name'] = $ckb?->name ?? '';
        $data['translation_ckb_bio'] = $ckb?->bio ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->translateOrNew('en')->fill([
            'name' => $this->data['translation_en_name'] ?? '',
            'bio' => $this->data['translation_en_bio'] ?? '',
        ])->save();

        $this->record->translateOrNew('ckb')->fill([
            'name' => $this->data['translation_ckb_name'] ?? '',
            'bio' => $this->data['translation_ckb_bio'] ?? '',
        ])->save();

        // Sync User name if journalist has a linked user
        if ($this->record->user_id && $this->record->user) {
            $newName = $this->data['translation_en_name']
                ?? $this->data['translation_ckb_name']
                ?? $this->record->user->name;

            if ($this->record->user->name !== $newName) {
                $this->record->user->update(['name' => $newName]);
            }
        }
    }
}
