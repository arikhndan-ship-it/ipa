<?php

namespace App\Filament\Resources\JournalistResource\Pages;

use App\Filament\Resources\JournalistResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateJournalist extends CreateRecord
{
    protected static string $resource = JournalistResource::class;

    protected function afterCreate(): void
    {
        $this->record->translateOrNew('en')->fill([
            'name' => $this->data['translation_en_name'] ?? '',
            'bio' => $this->data['translation_en_bio'] ?? '',
        ])->save();

        $this->record->translateOrNew('ckb')->fill([
            'name' => $this->data['translation_ckb_name'] ?? '',
            'bio' => $this->data['translation_ckb_bio'] ?? '',
        ])->save();

        // Auto-create User account
        $journalistName = $this->data['translation_en_name']
            ?? $this->data['translation_ckb_name']
            ?? 'Journalist';

        $username = Str::slug($journalistName, '');
        $email = $username . '@khandan.com';
        $password = $username . 'khandan';

        // Check if user already exists with this email
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $journalistName,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);
        }

        // Link the journalist to the user
        $this->record->update(['user_id' => $user->id]);
    }
}
