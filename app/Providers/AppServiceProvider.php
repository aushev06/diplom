<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Food;
use App\Observers\ClientObserver;
use App\Observers\FoodObserver;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Client::observe(ClientObserver::class);
        Food::observe(FoodObserver::class);
    }
}
