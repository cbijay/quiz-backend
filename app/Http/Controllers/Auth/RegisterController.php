<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use App\Services\Admin\StudentService;
use App\Services\Admin\UserService;
use App\Services\PaymentService;
use App\Session;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    protected $userService, $studentService, $paymentRepository, $paymentService;

    public function __construct(
        StudentService $studentService,
        UserService $userService,
        PaymentRepository $paymentRepository,
        PaymentService $paymentService
    ) {
        $this->userService = $userService;
        $this->studentService = $studentService;
        $this->paymentRepository = $paymentRepository;
        $this->paymentService = $paymentService;
    }

    public function register(Request $request)
    {
        try {
            $input = $request->all();

            if ($request->terms) {
                $file = $request->file('user_img');

                if ($file) {
                    $name = 'user_' . time() . $file->getClientOriginalName();
                    $file->move('images/users/', $name);
                    $input['user_img'] = $name;
                }

                $userData = [
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'role' => 'S',
                    'user_img' => $request->user_img ? $input['user_img'] : $request->user_img,
                    'status' => 0,
                ];

                $user = $this->userService->store($userData);

                $studentData = [
                    'grade' => $input['grade'],
                    'age'   => $input['age'],
                    'parents_name'  =>  $input['parents_name'],
                    'city'  =>  $input['city'],
                    'address' => $input['address'],
                    'phone_number'  =>  $input['phone_number'],
                    'user_id'   => $user->id,
                ];

                $student = $this->studentService->store($studentData);

                if ($user && $student) {
                    $paymentResponse = $this->paymentRepository->payment($input);

                    if ($paymentResponse->status === "succeeded") {
                        $paymentData = [
                            'payment_id' => $paymentResponse->id,
                            'subjects' => $request->subjects,
                            'amount' => $request->price,
                            'currency' => strtoupper($paymentResponse->currency),
                            'status' => $paymentResponse->status,
                            'user_id' => $user->id
                        ];

                        $payment = $this->paymentService->store($paymentData);

                        if ($payment) {
                            return response()->json(['message' => 'Payment Successful, User has been registered!!', 'register' => true]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1062) {
                return response()->json(['message' => 'Email already exists!!'], Response::HTTP_CONFLICT);
            }

            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
