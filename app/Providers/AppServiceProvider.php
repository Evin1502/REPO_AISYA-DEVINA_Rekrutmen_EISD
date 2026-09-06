<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        if ($this->app->runningInConsole()) {
            return;
        }

        // Keep CSS/JS and generated URLs correct when the app is served from
        // a subdirectory (XAMPP / Laravel public folder), not only artisan serve.
        URL::forceRootUrl(rtrim(request()->root(), '/'));

        Vite::createAssetPathsUsing(function (string $path, ?bool $secure = null) {
            $base = request()->getBasePath();

            return ($base === '' ? '' : $base).'/'.ltrim($path, '/');
        });
    }
}
