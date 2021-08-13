<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function store(array $data)
    {
        return $this->payment->create($data);
    }
}
