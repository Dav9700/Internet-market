<?php
namespace App\Classes;
use App\Models\Order;
use App\Models\Sku;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;
use App\Services\CurrencyConversion;


class Basket
{
    protected $order;
    /**
     * Basket constructor.
     * @param  bool  $createOrder
     */
    public function __construct($createOrder = false)
    {
        
        $order = session('order');
        if (is_null($order) && $createOrder) {
            $data = [];
            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }
            $data['currency_id'] = CurrencyConversion::getCurrentCurrencyFromSession()->id;

            $this->order = new Order($data);
            session(['order' => $this->order]);
        } else {
            $this->order = $order;
        }
    }
    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function countAvailable($updateCount = false)
    {
        $skus = collect([]);
        foreach ($this->order->skus as $orderSku)
        {
            $sku = Sku::find($orderSku->id);
            if ($orderSku->countInOrder > $sku->count) {
                return false;
            }
            if ($updateCount) {
                $sku->count -= $orderSku->countInOrder;
                $skus->push($sku);
            }
        }
        if ($updateCount) {
            $skus->map->save();
        }
        return true;
    }

    public function saveOrder($name, $phone, $email)
    {
        // dd('in safe');
        if (!$this->countAvailable(true)) {
            return false;
        }
        $this->order->saveOrder($name, $phone);

        Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));

        return true;
    }


    public function removeSku(Sku $sku)
    {
        if ($this->order->skus->contains($sku)) {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();
            if ($pivotRow->countInOrder < 2) {
                //dd($sku->id);
                //$sku->delete();
                //$delid=Sku::find($sku->id);
                //Sku::detach($sku->id);
                //dd($this->order);
                //dd($this->order->skus->where('id',$sku->id)->keys()->first());
                $this->order->skus->pull($this->order->skus->where('id',$sku->id)->keys()->first());//pull($sku);//->skus->pop();//where('id', $sku->id)->first()->remove();
                //dd($delsku);
                //$delsku->delete();
            } else {
                $pivotRow->countInOrder--;
            }
        }
    }

    public function addSku(Sku $sku)
    {
        
        if ($this->order->skus->contains($sku)) {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();
            if ($pivotRow->countInOrder >= $sku->count) {
                return false;
            }
            $pivotRow->countInOrder++;
        } else {
            if ($sku->count == 0) {
                return false;
            }
            $sku->countInOrder = 1;
            $this->order->skus->push($sku);
        }
        
        return true;
    }
}
