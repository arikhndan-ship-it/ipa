<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name_en', 'value' => 'Khandantelegraph', 'type' => 'text'],
            ['key' => 'site_name_ckb', 'value' => 'خەندان تێلێگراف', 'type' => 'text'],
            ['key' => 'website_url', 'value' => 'https://khandantelegraph.news', 'type' => 'text'],
            ['key' => 'website_domain', 'value' => 'khandantelegraph.news', 'type' => 'text'],
            ['key' => 'telegram_url', 'value' => 'https://t.me/khandantelegraph', 'type' => 'text'],
            ['key' => 'telegram_username', 'value' => '@khandantelegraph', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => 'https://www.facebook.com/share/194x5ECuH1/', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'contact@khandantelegraph.news', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
