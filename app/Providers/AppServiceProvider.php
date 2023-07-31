<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Company;
use App\Models\User;
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
        view()->share('content', Company::first());
        view()->share('areas', Area::all());
        view()->share('users', User::all());
    }
}
