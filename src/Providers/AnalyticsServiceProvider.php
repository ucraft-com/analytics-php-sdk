<?php

declare(strict_types = 1);

namespace Uc\Analytics\Providers;

use App\Application;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Uc\Analytics\Analytics;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'analytics');

        $this->app->bind(Analytics::class, function () {
            return new Analytics(
                new Client(),
                config('analytics.api_url')
            );
        });
    }
}
