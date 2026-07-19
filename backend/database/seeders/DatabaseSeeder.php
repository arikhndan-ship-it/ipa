<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@khandan.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create author user
        User::create([
            'name' => 'Author',
            'email' => 'author@khandan.com',
            'password' => bcrypt('password'),
            'role' => 'author',
        ]);

        // Create categories with EN + CKB translations
        $categories = [
            ['slug' => 'all', 'sort_order' => 0, 'en' => ['name' => 'All', 'description' => ''], 'ckb' => ['name' => 'هەموو', 'description' => '']],
            ['slug' => 'human-rights', 'sort_order' => 1, 'en' => ['name' => 'Human Rights', 'description' => ''], 'ckb' => ['name' => 'مافەکانی مرۆڤ', 'description' => '']],
            ['slug' => 'iran-regime', 'sort_order' => 2, 'en' => ['name' => 'Iranian Regime', 'description' => ''], 'ckb' => ['name' => 'دەسەڵاتی ئێران', 'description' => '']],
            ['slug' => 'arrests', 'sort_order' => 3, 'en' => ['name' => 'Arrests', 'description' => ''], 'ckb' => ['name' => 'بەندکردن', 'description' => '']],
            ['slug' => 'freedom-demands', 'sort_order' => 4, 'en' => ['name' => 'Freedom Demands', 'description' => ''], 'ckb' => ['name' => 'داواکاری ئازادی', 'description' => '']],
            ['slug' => 'courts', 'sort_order' => 5, 'en' => ['name' => 'Courts', 'description' => ''], 'ckb' => ['name' => 'دادگا', 'description' => '']],
            ['slug' => 'prison', 'sort_order' => 6, 'en' => ['name' => 'Prison', 'description' => ''], 'ckb' => ['name' => 'زیندان', 'description' => '']],
            ['slug' => 'economy', 'sort_order' => 7, 'en' => ['name' => 'Economy', 'description' => ''], 'ckb' => ['name' => 'ئابووری', 'description' => '']],
        ];

        foreach ($categories as $cat) {
            $category = Category::create([
                'slug' => $cat['slug'],
                'sort_order' => $cat['sort_order'],
                'is_active' => true,
            ]);
            CategoryTranslation::create(['category_id' => $category->id, 'locale' => 'en', 'name' => $cat['en']['name'], 'description' => $cat['en']['description']]);
            CategoryTranslation::create(['category_id' => $category->id, 'locale' => 'ckb', 'name' => $cat['ckb']['name'], 'description' => $cat['ckb']['description']]);
        }
    }
}
