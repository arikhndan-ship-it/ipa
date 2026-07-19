<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
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
