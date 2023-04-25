<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-sitemap {url : The URL to generate sitemap for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating sitemaps for sites';

    public function handle()
    {
        $validated = $this->validate();
        $browsershot = (new Browsershot())->noSandbox()->ignoreHttpsErrors();
        SitemapGenerator::create($validated['url'])
            ->configureCrawler(function (Crawler $crawler) use ($browsershot){
                $crawler->setBrowsershot($browsershot);
            })
            ->getSitemap()->writeToDisk('public', md5($validated['url']) . '/sitemap.xml');
        $this->info('The URL is: ' . $validated['url']);
        $this->info('The Folder is: ' . url(md5($validated['url'])) . '/sitemap.xml');
    }

    protected function validate(): array
    {
        $validator = Validator::make([
            'url' => $this->argument('url'),
        ], [
            'url' => ['required', 'url'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return [];
        }
        return $validator->validated();
    }
}

