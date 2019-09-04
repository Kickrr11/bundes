<?php

namespace App\Providers;

use App\Contracts\OpenLigaInterface;
use App\Services\OpenLigaDb;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(OpenLigaInterface::class, OpenLigaDb::class);
    }
}
