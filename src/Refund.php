<?php

namespace BfGasparin\Cashier;

use Braspag\Http\Sales;
use Illuminate\Database\Eloquent\Model;
use Braspag\Http\Exception\ApiException;
use BfGasparin\Cashier\Exceptions\RefundNotCompletedException;

class Refund extends Model
{

    protected $fillable = ['amount'];

    public function __construct(array $attributes = [])
    {
        $this->completed = false;
        parent::__construct($attributes);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function complete()
    {
        try {
            $response = app(Sales::class)->void($this->payment->braspag_id, $this->amount);
            $this->completed = true;
            $this->reason_code = $response->reasonCode;
            $this->reason_message = $response->reasonMessage;
            $this->provider_code = $response->providerResponseCode;
            $this->provider_message = $response->providerResponseMessage;

            $this->payment->status = $response->status;

            $this->save();
            $this->payment->save();

        } catch (ApiException $e) {
            $this->completed = false;
            $this->reason_code = $e->getCode();
            $this->reason_message = $e->getMessage();
            
            $this->save();

            throw new RefundNotCompletedException($e->getMessage(), $e->getCode());
        }
    }

    public function payment()
    {
        return $this->belongsTo('BfGasparin\Cashier\Payment');
    }
}
