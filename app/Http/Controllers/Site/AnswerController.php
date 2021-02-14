<?php

namespace App\Http\Controllers\Site;

use App\Events\ParticipantScore;
use App\Http\Controllers\Controller;
use App\Repositories\AnswerRepository;
use App\Services\AnswerService;
use Exception;
use Illuminate\Http\Request;
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
            $answer = $this->answerService->participantAnswer($input);

            if ($answer) {
                return response()->json([
                    'message' => 'Answer has already been submitted, please wait for few min and refresh the page',
                ], 400);
            }

            $userAnswer = $this->answerRepository->store($input);

            broadcast(new ParticipantScore())->toOthers();

            if ($userAnswer) {
                return response()->json($userAnswer);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}