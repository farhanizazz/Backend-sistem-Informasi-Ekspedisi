<?php

namespace App\Providers;

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
        $this->app->bind(
            'App\Repositories\Contracts\HutangSopirRepositoryInterface',
            'App\Repositories\Eloquent\HutangSopirRepository'
        );
        $this->app->bind(
            'App\Repositories\Contracts\SopirRepositoryInterface',
            'App\Repositories\Eloquent\SopirRepository'
        );
        $this->app->bind(
            'App\Repositories\Contracts\LaporanInterface',
            'App\Repositories\Eloquent\LaporanRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
