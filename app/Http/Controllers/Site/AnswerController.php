<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Repositories\AnswerRepository;
use App\Services\AnswerService;
use App\Events\ActiveQuestion;
use App\Events\ParticipantScore;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class AnswerController extends Controller
{
    //
    private $answerService, $answerRepository;

    public function __construct(
        AnswerService $answerService,
        AnswerRepository $answerRepository
    ) {
        $this->answerRepository = $answerRepository;
        $this->answerService = $answerService;
    }

    public function participantAnswer(Request $request)
    {
        try {
            $input = $request->all();
            $answer = $this->answerRepository->participantAnswer($input);

            if ($answer) {
                return response()->json([
                    'message' => 'Answer has already been submitted, please wait for few min and refresh the page',
                ], 400);
            }

            $userAnswer = $this->answerService->store($input);

            broadcast(new ParticipantScore())->toOthers();

            if ($userAnswer) {
                event(new ActiveQuestion());
                return response()->json($userAnswer);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}