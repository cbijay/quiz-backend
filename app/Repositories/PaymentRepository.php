<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\Subject;
use App\Models\User;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentRepository
{
    protected $stripeSecretKey, $payment, $user, $subject;

    public function __construct(Payment $payment, User $user, Subject $subject)
    {
        $this->stripeSecretKey = \config('stripe.secretKey');
        $this->payment = $payment;
        $this->user = $user;
        $this->subject = $subject;
    }

    public function payment($data)
    {
        Stripe::setApiKey($this->stripeSecretKey);
        $amount = $data['price'] * 100;

        $paymentIntent = PaymentIntent::create([
            'description' => 'NST School Quiz new registration payment',
            'currency' => 'usd',
            'amount' => $amount,
            'payment_method' => "pm_card_visa",
            'payment_method_types' => ['card'],
            'confirm' => true,
            'capture_method' => 'manual'
        ]);

        $intent = PaymentIntent::retrieve($paymentIntent->id);
        $intent->capture(['amount_to_capture' => $amount]);

        return $intent;
    }

    public function getPayments()
    {
        $payments = $this->payment->get();
        $users = collect();

        foreach ($payments as $payment) {
            $paymentUser = $this->user->where('id', $payment->user_id)->first();

            $student = (object)[
                'name' => $paymentUser->name,
                'subjects' => [],
                'payment_id' => $payment->payment_id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,
                'payment_date' => date("d/m/Y", strtotime($payment->created_at))
            ];

            $users->push($student);

            $subjectIds = explode(",", $payment->subjects);
            $studentSubject = $this->subject->whereIn('id', $subjectIds)->select('subject_name', 'price')->get();

            $student->subjects = $studentSubject;
        }

        return $users;
    }
}
