<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyOptionController;
use App\Http\Controllers\Admin\SkuController;


// use App\Http\Controllers;


Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::get('locale/{locale}', [MainController::class,'changeLocale'])->name('locale');
Route::get('currency/{currencyCode}', [MainController::class,'changeCurrency'])->name('currency');

Route::get('/logout', [LoginController::class,'logout'])->name('get-logout');

Route::get('/reset', [App\Http\Controllers\ResetController::class,'reset'])->name('reset');

Route::middleware(['set_locale'])->group(function () {
    
    Route::get('/reset', [App\Http\Controllers\ResetController::class,'reset'])->name('reset');

    Route::middleware(['auth'])->group(function () {
        Route::group([
            'prefix' => 'person',
            // 'namespace' => 'Person',
            'as' => 'person.',
        ], function () {
            Route::get('/orders', [App\Http\Controllers\Person\OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [App\Http\Controllers\Person\OrderController::class, 'show'])->name('orders.show');
        });
    
        Route::group([
            // 'namespace' => 'Admin',
            'prefix' => 'admin',
        ], function () {
            Route::group(['middleware' => 'is_admin'], function () {
                Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('home');
                Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');    
            });
            Route::resource('products', ProductController::class);
            Route::resource('categories',CategoryController::class);
            Route::resource('products/{product}/skus', SkuController::class);
            Route::resource('properties', PropertyController::class);
            Route::resource('properties/{property}/property-options', PropertyOptionController::class);
        });
    });
    
    
    
    // Route::get('/home', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('home');
    
    Route::get('/',[MainController::class,'index'])->name('index');
    
    
    
    Route::get('/categories', [MainController::class,'categories'])->name('categories');
    
    Route::post('subscription/{skus}',[MainController::class,'subscribe'])->name('subscription'); 
    
    Route::group(['prefix' => 'basket'], function () {
        Route::post('/basket/add/{skus}', [BasketController::class,'basketAdd'])->name('basket-add');
    
        Route::group([
            'middleware' => 'basket_not_empty',
        ], function () {
    
            Route::get('/', [BasketController::class,'basket'])->name('basket');
            Route::get('/place', [BasketController::class,'basketPlace'])->name('basket-place');
    
            Route::post('/remove/{skus}', [BasketController::class,'basketRemove'])->name('basket-remove');
    
            Route::post('/place', [BasketController::class,'basketConfirm'])->name('basket-confirm');
        });
    });
    
    
    Route::get('/{category}', [MainController::class,'category'])->name('category');
    
    Route::get('/{category}/{skus}', [MainController::class,'sku'])->name('sku');
    
    
});
//Route::get('/', [MainController::class,'category'])->name('category');

// Auth::routes();


