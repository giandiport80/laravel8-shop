<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GeneralServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/General.php'; // .. 1
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










// h: DOKUMENTASI
// mendaftarkan path file General.php
// setelah itu tambahkan providers nya ke config/app.php pada bagian providers (line 178)
// buat juga aliases nya (line 231)
