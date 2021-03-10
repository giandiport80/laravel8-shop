<?php

namespace App\Providers;

use App\Repositories\Front\CatalogRepository;
use App\Repositories\Front\Interfaces\CatalogRepositoryInterface;
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
