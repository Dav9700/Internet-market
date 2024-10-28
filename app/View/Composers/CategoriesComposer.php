<?php

namespace App\View\Composers;


use App\Models\Category;
use Illuminate\Contracts\View\View;
class CategoriesComposer
{
    public function compose(View $view)
    {
        $categories = Category::get();
        $view->with('categories', $categories);
    }
}