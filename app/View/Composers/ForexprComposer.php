<?php

namespace App\View\Composers;
use App\Models\Category;

// use Illuminate\View\View;
use Illuminate\Contracts\View\View;


class ForexprComposer
{
    public function compose(View $view)
    {
        $currencies =Category::get();
        // dd($view->getData('layouts.master'));
        $view->with('currencies', $currencies);
    }
}