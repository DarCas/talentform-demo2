<?php

namespace App\Providers;

use App\Repositories\FormRepository;
use App\Repositories\FormRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
//        $this->app->bind(FormRepositoryInterface::class, FormRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dico a Paginator di utilizzare il framework CSS Bootstrap 5
        Paginator::useBootstrapFive();
    }
}
