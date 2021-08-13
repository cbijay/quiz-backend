<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Admin\UserService;
use App\Services\Admin\StudentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    //
    protected $userService, $userRepository, $studentService;

    public function __construct(
        UserService $userService,
        UserRepository $userRepository,
        StudentService $studentService
    ) {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->studentService = $studentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = $this->userRepository->getStudent();
        return response()->json($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            $file = $request->file('image');

            if ($file) {
                $name = 'user_' . time() . $file->getClientOriginalName();
                $file->move('images/users/', $name);
                $input['user_img'] = $name;
            }

            $userData = [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'mobile' => $request->mobile ? $input['mobile'] : "",
                'address' => $input['address'],
                'city' => $input['city'],
                'role' => $input['role'],
                'user_img' => $input['user_img'],
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
                return response()->json([$user, $student]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $user = $this->userRepository->withById($id, 'student');

            $student = [
                'id'    => $user->id,
                'name'  =>  $user->name,
                'email' =>  $user->email,
                'role'  =>  $user->role,
                'status'    =>  $user->role,
                'image'  =>  $user->user_img,
                'is_online' =>  $user->is_online,
                'grade' =>  $user->student->grade,
                'age'   =>  $user->student->age,
                'parents_name'  =>  $user->student->parents_name,
                'city'  =>  $user->student->city,
                'address'   => $user->student->address,
                'phone_number'  =>  $user->student->phone_number
            ];

            return response()->json($student);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->getById($id);

            $input = $request->all();

            $file = $request->file('user_img');

            if ($file) {
                $name = 'user_' . time() . $file->getClientOriginalName();
                $file->move('images/users/', $name);
                $input['user_img'] = $name;
            }

            $userData = [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $request->password ? Hash::make($input['password']) : $user->password,
                'mobile' => $request->mobile ? $input['mobile'] : "",
                'address' => $input['address'],
                'city' => $input['city'],
                'role' => $input['role'],
                'user_img' => $input['user_img'],
                'status' => 0,
            ];

            //return response()->json($input);

            $updatedUser = $this->userService->update($id, $userData);

            $studentData = [
                'grade' => $input['grade'],
                'age'   => $input['age'],
                'parents_name'  =>  $input['parents_name'],
                'city'  =>  $input['city'],
                'address' => $input['address'],
                'phone_number'  =>  $input['phone_number'],
                'user_id'   => $user->id,
            ];

            $updatedStudent = $this->studentService->update($user->student->id, $studentData);

            $student = $this->userRepository->withById($id, 'student');

            if ($updatedUser && $updatedStudent) {
                return response()->json($student);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $answers = $this->userRepository->userAnswers($id);

            if (count($answers) > 0) {
                return response()->json(['message' => 'Please reset user answers from student report before deleting this user']);
            } else {
                $student = $this->userService->destroy($id);

                if ($student) {
                    return response()->json($id);
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $updateStatus = $this->userRepository->updateStatus($id, $status);

            if ($updateStatus) {
                $student = $this->userRepository->getStudent();
                return response()->json($student);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function latest()
    {
        $students = $this->userRepository->latestStudent();
        return response()->json($students);
    }

    public function participants()
    {
        $students = $this->userRepository->participants();
        return response()->json($students);
    }
}
