<?php

namespace App\Providers;

use App\Repositories\Front\CartRepository;
use App\Repositories\Front\CatalogRepository;
use App\Repositories\Front\Interfaces\CartRepositoryInterface;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Front\Interfaces\OrderRepositoryInterface;
use App\Repositories\Front\OrderRepository;
use Illuminate\Support\ServiceProvider;

class FrontRespositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CatalogRepositoryInterface::class,
            CatalogRepository::class,
        );

        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
