<?php

namespace App\Services;

use Exception;
use Omnipay\Omnipay;

class PaypalService
{
    public $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function charge($data)
    {

        $paymentResponse = $this->gateway->purchase(array(
            'amount' => $data['price'],
            'currency' => env('PAYPAL_CURRENCY'),
            'description' => 'NST school user registration',
            'returnUrl' => route('payment_success'),
            'cancelUrl' => url('paymenterror'),
        ))->send();

        dd($paymentResponse);
        if ($paymentResponse->isRedirect()) {
            return response()->json('redirecting....');
            //$response->redirect();
        } else {
            // not successful
            return response()->json(['message' => $paymentResponse->getMessage()]);
        }
    }
}