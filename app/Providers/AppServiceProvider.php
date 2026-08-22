<?php

namespace App\Providers;

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
        $modulesPath = base_path('app/Modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Load Module Migrations
        |--------------------------------------------------------------------------
        */

        $migrationPaths = glob(
            $modulesPath . '/*/database/migrations',
            GLOB_ONLYDIR
        );

        foreach ($migrationPaths as $migrationPath) {
            $this->loadMigrationsFrom($migrationPath);
        }

        /*
        |--------------------------------------------------------------------------
        | Load Module API Routes
        |--------------------------------------------------------------------------
        */

        $routeFiles = glob(
            $modulesPath . '/*/routes/api.php'
        );

        foreach ($routeFiles as $routeFile) {
            if (file_exists($routeFile)) {
                $this->loadRoutesFrom($routeFile);
            }
        }
    }
}