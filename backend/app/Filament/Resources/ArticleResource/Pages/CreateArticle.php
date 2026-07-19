<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
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

        // Fire notification AFTER translations exist
        \App\Services\NotificationService::articleCreated($this->record);
    }
}
