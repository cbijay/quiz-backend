<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Services\Admin\StudentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    //
    protected $studentRepository, $studentService;

    public function __construct(
        UserRepository $studentRepository,
        StudentService $studentService
    ) {
        $this->studentRepository = $studentRepository;
        $this->studentService = $studentService;
    }

    public function latest()
    {
        $students = $this->studentService->latestStudent();
        return response()->json($students);
    }

    public function participants()
    {
        $students = $this->studentService->participants();
        return response()->json($students);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = $this->studentService->getStudent();
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

            if ($file = $request->file('user_img')) {

                $name = 'user_' . time() . $file->getClientOriginalName();
                $file->move('images/users/', $name);
                $input['user_img'] = $name;
            }

            $data = [
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

            $student = $this->studentRepository->store($data);


            if ($student) {
                return response()->json($student);
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
            $student = $this->studentRepository->getById($id);

            if ($student) {
                return response()->json($student);
            }
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
            $student = $this->studentRepository->getById($id);

            $input = $request->all();
            $input['user_img'] = $student->user_img;

            if ($file = $request->file('user_img')) {

                $name = 'user_' . time() . $file->getClientOriginalName();

                if ($student->user_img != null) {
                    unlink(public_path() . '/images/users/' . $student->user_img);
                }

                $file->move('images/users/', $name);
                $input['user_img'] = $name;
            }

            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'mobile' => $input['mobile'],
                'address' => $input['address'],
                'city' => $input['city'],
                'role' => $input['role'],
                'user_img' => $input['user_img'],
            ];

            $updatedStudent = $this->studentRepository->update($id, $data);

            if ($updatedStudent) {
                return response()->json($updatedStudent);
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
            $student = $this->studentRepository->destroy($id);

            if ($student) {
                return response()->json($id);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $updateStatus = $this->studentService->updateStatus($id, $status);

            if ($updateStatus) {
                $student = $this->studentService->getStudent();
                return response()->json($student);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}