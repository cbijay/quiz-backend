<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{
    //
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        $payments = $this->paymentRepository->getPayments();

        return response()->json($payments);
    }
}
