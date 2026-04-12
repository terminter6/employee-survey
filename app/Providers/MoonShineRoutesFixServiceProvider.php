<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MoonShineRoutesFixServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $moonshineRoutesPath = base_path('vendor/moonshine/moonshine/src/routes/moonshine.php');
        $moonshineLaravelRoutesPath = base_path('vendor/moonshine/moonshine/src/Laravel/routes/moonshine.php');

        if (!file_exists($moonshineRoutesPath) && file_exists($moonshineLaravelRoutesPath)) {
            $directory = dirname($moonshineRoutesPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            copy($moonshineLaravelRoutesPath, $moonshineRoutesPath);
        }
    }
}
