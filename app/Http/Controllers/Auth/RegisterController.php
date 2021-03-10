<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Services\PaypalService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    protected $userRepository, $studentRepository, $paypalService;

    public function __construct(
        UserRepository $userRepository,
        StudentRepository $studentRepository,
        PaypalService $paypalService
    ) {
        $this->userRepository = $userRepository;
        $this->studentRepository = $studentRepository;
        $this->paypalService = $paypalService;
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

                $user = $this->userRepository->store($userData);

                $studentData = [
                    'grade' => $input['grade'],
                    'age'   => $input['age'],
                    'parents_name'  =>  $input['parents_name'],
                    'city'  =>  $input['city'],
                    'address' => $input['address'],
                    'phone_number'  =>  $input['phone_number'],
                    'user_id'   => $user->id,
                ];

                $student = $this->studentRepository->store($studentData);

                $subjects = explode(',', $input['subjects']);

                $student->subjects()->attach($subjects);

                if ($user && $student) {
                    return response()->json([$user, $student]);
                }
            } else {
                return response()->json(['message' => 'Please accept terms and conditions']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}