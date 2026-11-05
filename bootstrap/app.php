<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [\App\Http\Middleware\ShowMigrationNotice::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Shared hosting keeps app_code and public_html next to each other. Apply the
// same public path for Artisan as the hosting index.php uses for web requests.
$hostingPublicPath = dirname(__DIR__, 2).'/public_html';
if (! is_dir(dirname(__DIR__).'/public') && is_dir($hostingPublicPath)) {
    $app->usePublicPath($hostingPublicPath);
}

return $app;
