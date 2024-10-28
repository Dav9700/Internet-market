<?php

namespace App\View\Composers;

use App\Services\CurrencyConversion;
// use Illuminate\View\View;
use Illuminate\Contracts\View\View;


class CurrenciesComposer
{
    public function compose(View $view)
    {
        $currencies = CurrencyConversion::getCurrencies();
        // dd($view->getData('layouts.master'));
        $view->with('currencies', $currencies);
    }
}
