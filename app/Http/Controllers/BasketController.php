<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sku;
use App\Classes\Basket;
use Illuminate\Support\Facades\Auth;


class BasketController extends Controller
{
    public function basket()
    {
        // $orderId = session('orderId');
        
        // if (!is_null($orderId)) {
        //     $order = Order::findOrFail($orderId);
        // }
        // $order = Order::findOrFail($orderId);
        
        $order = (new Basket())->getOrder();
        
        return view('basket', compact('order'));
    }

    public function basketPlace()
    {
        $basket = new Basket();
        $order = $basket->getOrder();
        if (!$basket->countAvailable()) {
            session()->flash('warning', __('basket.you_cant_order_more'));
            return redirect()->route('basket');
        }
        
        return view('order', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $email = Auth::check() ? Auth::user()->email : $request->email;
        if ((new Basket())->saveOrder($request->name, $request->phone, $email)) {
            session()->flash('success', __('basket.you_order_confirmed'));
        } else {
            session()->flash('warning', __('basket.you_cant_order_more'));
        }
        
        return redirect()->route('index');
    }

    public function basketAdd(Sku $skus)
    {
        $result = (new Basket(true))->addSku($skus);

        if ($result) {
            session()->flash('success', __('basket.added').$skus->product->__('name'));
        } else {
            session()->flash('warning', $skus->product->__('name') . __('basket.not_available_more'));
        }

        return redirect()->route('basket');
    }

    public function basketRemove(Sku $skus)
    {
        (new Basket())->removeSku($skus);
        
        session()->flash('warning', __('basket.removed').$skus->product->__('name'));
        
        return redirect()->route('basket');
    }
}
