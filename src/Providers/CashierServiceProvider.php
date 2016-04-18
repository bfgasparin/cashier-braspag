<?php

namespace BfGasparin\Cashier\Providers;

use Braspag\Http\Sales;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class CashierServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Sales::class, function ($app) {
            return new Sales(config('braspag.merchantId'), config('braspag.merchantKey'));
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->isLumen()){
            $this->app->configure('braspag');
        }else{
            $this->publishes([
                __DIR__.'/../config/braspag.php' => config_path('braspag.php'),
            ]);
        }
    }

    private function isLumen()
    {
        return (app() instanceof LumenApplication);
    }    
}
