<?php

namespace Spatie\Sitemap;

use Spatie\Crawler\Crawler;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sitemap.php' => config_path('sitemap.php'),
        ], 'config');

        $this->app->when(SitemapGenerator::class)
            ->needs(Crawler::class)
            ->give(function () {
                return Crawler::create(config('sitemap.guzzle_options'));
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sitemap.php', 'sitemap');
    }
}
