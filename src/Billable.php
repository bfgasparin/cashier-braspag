<?php

namespace BfGasparin\Cashier;

use Exception;
use Braspag\Sale;
use Braspag\Http\Sales;
use BadMethodCallException;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use BfGasparin\Cashier\Exceptions\PaymentAlreadyRefundedException;

trait Billable
{
    /**
     * The Braspag MerchantKey.
     *
     * @var string
     */
    protected static $merchantKey;

    /**
     * The Braspag MerchantId.
     *
     * @var string
     */
    protected static $merchantId;    
    
    /**
     * Find a charge by ID.
     *
     * @param  string  $id
     * @return \Braspag\Sale
     */
    public function findCharge($id)
    {
        $sales= app(Sales::class);

        return $sales->get($id);
    }

    /**
     * Refunds the given payment with the given $amount
     * @param  Payment $payment
     * @param  integer $amount 
     * @return Void   
     */
    public function refund(Payment $payment, $amount)
    {
        if ($payment->isRefunded()){
            throw new PaymentAlreadyRefundedException("Payment is already refunded");
        }

        $refund = $payment->refunds()->create(['amount' => $amount]);

        $refund->complete();

        return $refund;
    }

    public function setParameter($id, $value)
    {
        $this->params[$id] = $value;

    }
    /**
     * Make a "one off" charge on the customer for the given amount.
     *
     * @param  int  $amount
     * @param  array  $options
     * @return \Braspag\BraspagSale
     */
    public function charge($amount, array $options = [])
    {

        $sales = app(Sales::class);
        $sale = new Sale([
            'merchantOrderId' => $options['order_id'],
            'customer' => [
                'name' => "Comprador de Testes",
                'email' => "compradordetestes@braspag.com.br",
                'birthdate' => "1991-01-02",
                'address' => [
                    'city' => "Rio de Janeiro",
                    'complement' => "Sala 934",
                    'country' => "BRA",
                    'district' => "Centro",
                    'number' => "160",
                    'state' => "RJ",
                    'street' => "Av. Marechal Câmara",
                    'zipCode' => "20020-080"]
                ],
            'payment' => [
                'Type' => 'CreditCard',
                'amount' => $amount,
                'provider' => "Simulado",
                'installments' => 3,
                'creditCard' => [
                    'brand' => "Visa",
                    'cardNumber' => "4532117080573700",
                    'expirationDate' => "12/2015",
                    'holder' => "Test T S Testando",
                    'securityCode' => "000",
                ]
            ]
        ]);

        return $sales->createSale($sale);
        // $customer = $this->asBraspagCustomer();
        // $response = BraspagSales::sale(array_merge([
        //     'amount' => $amount * (1 + ($this->taxPercentage() / 100)),
        //     'paymentMethodToken' => $customer->paymentMethods[0]->token,
        //     'options' => [
        //         'submitForSettlement' => true,
        //     ],
        //     'recurring' => true,
        // ], $options));
        // if (! $response->success) {
        //     throw new Exception('Braspag was unable to perform a charge: '.$response->message);
        // }
        // return $response;
    }
   

    /**
     * Invoice the customer for the given amount.
     *
     * @param  string  $description
     * @param  int  $amount
     * @param  array  $options
     * @return \Braspag\BraspagSale
     */
    public function invoiceFor($description, $amount, array $options = [])
    {
        // return $this->charge($amount, array_merge($options, [
        //     'customFields' => [
        //         'description' => $description,
        //     ],
        // ]));
    }
    /**
     * Begin creating a new subscription.
     *
     * @param  string  $subscription
     * @param  string  $plan
     * @return \Gasparin\Cashier\SubscriptionBuilder
     */
    public function newSubscription($subscription, $plan)
    {
        throw new \BadMethodCallException('Not implemented yet');
        
    }
    /**
     * Determine if the user is on trial.
     *
     * @param  string  $subscription
     * @param  string|null  $plan
     * @return bool
     */
    public function onTrial($subscription = 'default', $plan = null)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Determine if the user is on a "generic" trial at the user level.
     *
     * @return bool
     */
    public function onGenericTrial()
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Determine if the user has a given subscription.
     *
     * @param  string  $subscription
     * @param  string|null  $plan
     * @return bool
     */
    public function subscribed($subscription = 'default', $plan = null)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Get a subscription instance by name.
     *
     * @param  string  $subscription
     * @return \Gasparin\Cashier\Subscription|null
     */
    public function subscription($subscription = 'default')
    {
       throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Get all of the subscriptions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Find an invoice by ID.
     *
     * @param  string  $id
     * @return \Gasparin\Cashier\Invoice|null
     */
    public function findInvoice($id)
    {
       throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Find an invoice or throw a 404 error.
     *
     * @param  string  $id
     * @return \Gasparin\Cashier\Invoice
     */
    public function findInvoiceOrFail($id)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Create an invoice download Response.
     *
     * @param  string  $id
     * @param  array   $data
     * @param  string  $storagePath
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadInvoice($id, array $data, $storagePath = null)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Get a collection of the entity's invoices.
     *
     * @param  bool  $includePending
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     */
    public function invoices($includePending = false, $parameters = [])
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Get an array of the entity's invoices.
     *
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     */
    public function invoicesIncludingPending(array $parameters = [])
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Update customer's credit card.
     *
     * @param  string  $token
     * @return void
     */
    public function updateCard($token)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Update the payment method token for all of the user's subscriptions.
     *
     * @param  string  $token
     * @return void
     */
    protected function updateSubscriptionsToPaymentMethod($token)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Apply a coupon to the billable entity.
     *
     * @param  string  $coupon
     * @param  string $subscription
     * @param  bool  $removeOthers
     * @return void
     */
    public function applyCoupon($coupon, $subscription = 'default', $removeOthers = false)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Determine if the user is actively subscribed to one of the given plans.
     *
     * @param  array|string  $plans
     * @param  string  $subscription
     * @return bool
     */
    public function subscribedToPlan($plans, $subscription = 'default')
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Determine if the entity is on the given plan.
     *
     * @param  string  $plan
     * @return bool
     */
    public function onPlan($plan)
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
    /**
     * Create a Braspag customer for the given user.
     *
     * @param  string  $token
     * @param  array  $options
     * @return \Braspag\BraspagCustomer
     */
    public function createAsBraspagCustomer($token, array $options = [])
    {
        // $response = BraspagCustomer::create(
        //     array_replace_recursive($options, [
        //         'firstName' => Arr::get(explode(' ', $this->name), 0),
        //         'lastName' => Arr::get(explode(' ', $this->name), 1),
        //         'email' => $this->email,
        //         'paymentMethodNonce' => $token,
        //         'creditCard' => [
        //             'options' => [
        //                 'verifyCard' => true,
        //             ]
        //         ],
        //     ])
        // );
        // if (! $response->success) {
        //     throw new Exception('Unable to create Braspag customer: '.$response->message);
        // }
        // $paymentMethod = $response->customer->paymentMethods[0];
        // $paypalAccount = $paymentMethod instanceof PaypalAccount;
        // $this->forceFill([
        //     'braspag_id' => $response->customer->id,
        //     'paypal_email' => $paypalAccount ? $paymentMethod->email : null,
        //     'card_brand' => ! $paypalAccount ? $paymentMethod->cardType : null,
        //     'card_last_four' => ! $paypalAccount ? $paymentMethod->last4 : null,
        // ])->save();
        // return $response->customer;
    }

    /**
     * Get the Braspag customer for the user.
     *
     * @return \Braspag\BraspagCustomer
     */
    public function asBraspagCustomer()
    {
        // return BraspagCustomer::find($this->braspag_id);
    }
    /**
     * Determine if the entity has a Braspag customer ID.
     *
     * @return bool
     */
    public function hasBraspagId()
    {
        return ! is_null($this->braspag_id);
    }
}