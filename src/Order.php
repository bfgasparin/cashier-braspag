<?php

namespace BfGasparin\Cashier;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['description', 'braspag_id'];

    public function user()
    {
        return $this->belongsTo(config('braspag.user'));
    }

    public function payments()
    {
        return $this->hasMany('BfGasparin\Cashier\Payment');
    }
}
