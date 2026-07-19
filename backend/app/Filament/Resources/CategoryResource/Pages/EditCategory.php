<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

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
        $data['translation_en_description'] = $en?->description ?? '';

        $ckb = $record->translations->firstWhere('locale', 'ckb');
        $data['translation_ckb_name'] = $ckb?->name ?? '';
        $data['translation_ckb_description'] = $ckb?->description ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->translateOrNew('en')->fill([
            'name' => $this->data['translation_en_name'] ?? '',
            'description' => $this->data['translation_en_description'] ?? '',
        ])->save();

        $this->record->translateOrNew('ckb')->fill([
            'name' => $this->data['translation_ckb_name'] ?? '',
            'description' => $this->data['translation_ckb_description'] ?? '',
        ])->save();
    }
}
