<?php

namespace App\Providers;

use App\Models\CategoryCache;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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

        $categories = DB::table('home_page_caches')->select('category_caches')->first();
        $categories =  json_decode($categories->category_caches);


        $offers = Offer::all();
        View::share('categories', $categories);
        View::share('offers', $offers);
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
