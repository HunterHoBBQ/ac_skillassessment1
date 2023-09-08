<?php

namespace App\Providers;
use App\Models\Bid;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
        Validator::extend('min_bid_price', function ($attribute, $value, $parameters, $validator) {
            $latestBidPrice = Bid::max('price');
            return $value > $latestBidPrice;
        }, 'The bid price cannot be lower than the latest bid price.');
    }
}
