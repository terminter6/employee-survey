<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

// Clear potentially invalid APP_URL from system environment FIRST
$systemAppUrl = getenv('APP_URL');
if ($systemAppUrl && (str_contains($systemAppUrl, 'ваш-домен') || str_contains($systemAppUrl, 'beget'))) {
    putenv('APP_URL');
    unset($_ENV['APP_URL'], $_SERVER['APP_URL']);
}

// Dynamically set APP_URL based on request host for Laravel Herd
$requestHost = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? null;
if ($requestHost) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $dynamicUrl = $protocol . '://' . $requestHost;
    putenv('APP_URL=' . $dynamicUrl);
    $_ENV['APP_URL'] = $dynamicUrl;
    $_SERVER['APP_URL'] = $dynamicUrl;
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/moonshine.php'));
        },
    )
    ->withProviders([
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\MoonShineServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustHosts();
        $middleware->validateCsrfTokens(except: [
            'admin/logout',
            'admin*/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
