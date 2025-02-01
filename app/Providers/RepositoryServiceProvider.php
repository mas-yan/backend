<?php

namespace App\Providers;

use App\Interfaces\Repositories\CommissionRepositoryInterface;
use App\Interfaces\Repositories\PaymentRepositoryInterface;
use App\Repositories\CommissionRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(
            CommissionRepositoryInterface::class,
            CommissionRepository::class
        );

        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
