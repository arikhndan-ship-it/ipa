<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

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
        $data['translation_en_title'] = $en?->title ?? '';
        $data['translation_en_body'] = $en?->body ?? '';
        $data['translation_en_excerpt'] = $en?->excerpt ?? '';

        $ckb = $record->translations->firstWhere('locale', 'ckb');
        $data['translation_ckb_title'] = $ckb?->title ?? '';
        $data['translation_ckb_body'] = $ckb?->body ?? '';
        $data['translation_ckb_excerpt'] = $ckb?->excerpt ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->translateOrNew('en')->fill([
            'title' => $this->data['translation_en_title'] ?? '',
            'body' => $this->data['translation_en_body'] ?? '',
            'excerpt' => $this->data['translation_en_excerpt'] ?? '',
        ])->save();

        $this->record->translateOrNew('ckb')->fill([
            'title' => $this->data['translation_ckb_title'] ?? '',
            'body' => $this->data['translation_ckb_body'] ?? '',
            'excerpt' => $this->data['translation_ckb_excerpt'] ?? '',
        ])->save();

        // Sync single category
        $categoryId = (int) ($this->data['categories'] ?? 0);
        if ($categoryId > 0) {
            $this->record->categories()->sync([$categoryId]);
        }
    }
}
