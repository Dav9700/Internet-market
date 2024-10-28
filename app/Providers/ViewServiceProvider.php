<?php

namespace App\Providers;

use App\Services\CurrencyConversion;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

use App\View\Composers;



class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //dd('in boot');
        //View::composer('layouts.master',Composers\CurrenciesComposer::class);

        
        View::composer('layouts.master',Composers\CurrenciesComposer::class);
        View::composer(['layouts.master', 'categories'], Composers\CategoriesComposer::class);
        
       
       View::composer('layouts.master',Composers\BestProductsComposer::class); //function ($view) { 
            
        
        View::composer('*', function ($view) {
            $currencySymbol = CurrencyConversion::getCurrencySymbol();
            $view->with('currencySymbol', $currencySymbol);
        });
    }
    
}
