<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\QuestionService;

class QuestionController extends Controller
{
    //
    protected $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function activeQuestion()
    {
        $question = $this->questionService->activeQuestion();

        return response()->json($question);
    }
}
