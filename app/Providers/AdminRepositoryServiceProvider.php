<?php

namespace App\Providers;

use App\Repositories\Admin\AttributeRepository;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Admin\CategoryRepository;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use App\Repositories\Admin\ProductRepository;
use Illuminate\Support\ServiceProvider;

class AdminRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class,
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class,
        );

        $this->app->bind(
            AttributeRepositoryInterface::class,
            AttributeRepository::class,
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
