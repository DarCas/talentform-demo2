<?php

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$repository = RepositoryBuilder::createWithDefaultAdapters()
    ->immutable()
    ->make();

$dotenv = Dotenv::create($repository, __DIR__ . '/../', '.env');
$dotenv->load();

if (file_exists(__DIR__ . '/../.env.development')) {
    $dotenvDev = Dotenv::create($repository, __DIR__ . '/../', '.env.development');
    $dotenvDev->load();
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
