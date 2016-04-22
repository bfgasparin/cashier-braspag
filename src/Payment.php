<?php

namespace BfGasparin\Cashier;

use Braspag\Http\Sales;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $appends = ['last_refunded_at'];

    protected $fillable = ['amount', 'braspag_id', 'status', 'canceled_at'];

    public function order()
    {
        return $this->belongsTo('BfGasparin\Cashier\Order');
    }

    public function refunds()
    {
        return $this->hasMany('BfGasparin\Cashier\Refund');
    }

    public function isRefunded()
    {
        return $this->status === 10;
    }

    public function getLastRefundedAtAttribute()
     {
         return $this->refunds()->completed()->latest('created_at')->first()->created_at;
     } 
    /**
     * Get the user for the payment
     *
     * @return User
     */
    public function getUserAttribute()
    {
        return $this->order->user;
    }

    /**
     * Get braspag data for the payment
     *
     * @param  string  $value
     * @return Braspag\Sale
     */
    public function getBraspagDataAttribute()
    {
        return app(Sales::class)->get($this->braspag_id)->payment->toArray();
    }

}
