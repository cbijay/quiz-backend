<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Site\QuestionController;
use App\Http\Controllers\Site\AnswerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

//Auth Route
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post("/register", [RegisterController::class, 'register']);
    Route::post("/login", [LoginController::class, 'login']);
});

Route::group(['prefix' => 'v1'], function () {
    //Admin Routes
    route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        //Student Route
        Route::get('/students/latest', [StudentController::class, 'latest']);
        Route::get('/students/participants', [StudentController::class, 'participants']);
        Route::get('/students', [StudentController::class, 'index']);
        Route::post('/students', [StudentController::class, 'store']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
        Route::put('/students/{id}', [StudentController::class, 'update']);
        Route::delete('/students/{id}', [StudentController::class, 'destroy']);
        Route::post('/students/{id}/{status}', [StudentController::class, 'updateStatus']);

        //Topics Route
        Route::get('/topics', [TopicController::class, 'index']);
        Route::post('/topics', [TopicController::class, 'store']);
        Route::get('/topics/{id}', [TopicController::class, 'show']);
        Route::put('/topics/{id}', [TopicController::class, 'update']);
        Route::delete('/topics/{id}', [TopicController::class, 'destroy']);

        //Questions Route
        Route::get('/questions', [AdminQuestionController::class, 'allQuestions']);
        Route::put('/questions/{id}/updateStatus', [AdminQuestionController::class, 'updateStatus']);
        Route::get('/questions/topics', [AdminQuestionController::class, 'topics']);
        Route::get('/questions/topics/{topicId}', [AdminQuestionController::class, 'index']);
        Route::post('/questions/topics/{topicId}', [AdminQuestionController::class, 'store']);
        Route::post('/questions/import/{topicId}', [AdminQuestionController::class, 'importExcel']);
        Route::get('/questions/{id}', [AdminQuestionController::class, 'show']);
        Route::put('/questions/{id}', [AdminQuestionController::class, 'update']);
        Route::delete('/questions/{id}', [AdminQuestionController::class, 'destroy']);

        //Report Route
        Route::get('/reports/topics/{topicId}', [ReportController::class, 'index']);
    });

    //Site Routes
    Route::group(['namespace' => 'Site'], function () {
        Route::get('/questions/active', [QuestionController::class, 'activeQuestion']);
        Route::post('/participant/answer',  [AnswerController::class, 'participantAnswer']);
    });
});