<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the website';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = \Spatie\Sitemap\Sitemap::create();

        // Add static pages
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY));

        $sitemap->add(Url::create('/about')
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        $sitemap->add(Url::create('/contact')
            ->setPriority(0.3)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));

        $sitemap->add(Url::create('/privacy')
            ->setPriority(0.3)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        $sitemap->add(Url::create('/terms')
            ->setPriority(0.3)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Add all published articles
        $articles = \App\Models\Article::published()->get();
        foreach ($articles as $article) {
            $sitemap->add(Url::create("/articles/{$article->slug}")
                ->setLastModificationDate($article->updated_at)
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        }

        // Add categories
        $categories = \App\Models\Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $sitemap->add(Url::create("/categories/{$category->slug}")
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
