<?php

namespace App\Services;

use App\Http\Controllers\Site\PaymentController;
use Omnipay\Omnipay;
use App\Models\Payment;
use Illuminate\Support\Facades\URL;

class PaypalService
{
    public $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(config('paypal.credentials.client_id'));
        $this->gateway->setSecret(config('paypal.credentials.client_secret'));
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function charge($data)
    {
        $response = $this->gateway->purchase(array(
            'amount' => $data['price'],
            'currency' => 'USD',
            'description' => 'NST school user registration',
            'returnUrl' => action([PaymentController::class, 'payment_success']),
            'cancelUrl' => url('paymenterror'),
        ))->send();

        if ($response->isSuccessful()) {
            dd($response);
        } elseif ($response->isRedirect()) {
            $responseData = $response->getData();
            //dd($response);
            //return response()->json($responseData['links'][1]);
        } else {
            return response()->json(['message' => $response->getMessage()]);
        }
    }

    public function payment_success($request)
    {
        $transaction = $this->gateway->completePurchase(array(
            'payer_id'             => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ));
        $response = $transaction->send();

        if ($response->isSuccessful()) {
            // The customer has successfully paid.
            $arr_body = $response->getData();

            // Insert transaction data into the database
            $isPaymentExist = Payment::where('payment_id', $arr_body['id'])->first();

            if (!$isPaymentExist) {
                $payment = new Payment;
                $payment->payment_id = $arr_body['id'];
                $payment->payer_id = $arr_body['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr_body['payer']['payer_info']['email'];
                $payment->amount = $arr_body['transactions'][0]['amount']['total'];
                $payment->currency = 'USD';
                $payment->payment_status = $arr_body['state'];
                $payment->save();

                return response()->json($payment);
            }
            return response()->json($isPaymentExist);
        } else {
            return $response->getMessage();
        }
    }
}