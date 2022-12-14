<?php

declare(strict_types = 1);

namespace Uc\Analytics\Providers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Uc\Analytics\Analytics;

/**
 * @package Uc\Analytics\Providers
 */
class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'analytics');

        $this->app->bind(Analytics::class, function () {
            return new Analytics(
                new Client(),
                config('analytics.api_url'),
                [
                    'library_version'            => config('analytics.library_version'),
                    'analytics_tracking_enabled' => config('analytics.analytics_tracking_enabled'),
                ],
            );
        });
    }
}
