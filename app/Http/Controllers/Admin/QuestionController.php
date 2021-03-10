<?php

namespace App\Http\Controllers\Admin;

use App\Events\ActiveQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\QuestionRepository;
use App\Services\Admin\QuestionService;
use Exception;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    //
    protected $questionRepository, $questionService;

    public function __construct(
        QuestionRepository $questionRepository,
        QuestionService $questionService
    ) {
        $this->questionRepository = $questionRepository;
        $this->questionService = $questionService;
    }

    public function askedQuestion()
    {
        $questions = $this->questionService->askedQuestion();

        return response()->json($questions);
    }

    public function allQuestions()
    {
        $topics = $this->questionRepository->get();

        return response()->json($topics);
    }

    public function updateStatus($topicId, $id, $status)
    {
        try {
            $updateStatus = $this->questionService->updateStatus($id, $status);

            if ($updateStatus) {
                $questions = $this->questionService->getByTopics($topicId);
                return response()->json($questions);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function openQuestion($id, $status)
    {
        try {
            $question = $this->questionService->openQuestion($id, $status);

            event(new ActiveQuestion());

            if ($question) {
                return response()->json($question);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function resetTimer($id, $status)
    {
        try {
            $question = $this->questionService->resetTimer($id, $status);

            if ($question) {
                return response()->json($question);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function topics()
    {
        $topics = $this->questionService->getTopics();

        return response()->json($topics);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicId)
    {
        $questions = $this->questionService->getByTopics($topicId);

        return response()->json($questions);
    }

    /**
     * Import a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importExcel($topicId, Request $request)
    {
        try {
            if ($request->hasFile('question_file')) {
                $file = $request->file('question_file');
                $excel = $this->questionService->importExcel($topicId, $file);
            }

            if ($excel) {
                return response()->json($excel);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($topicId, Request $request)
    {
        try {
            $data = $request->all();
            $data['topic_id'] = $topicId;

            if ($file = $request->file('question_img')) {
                $name = 'question_' . time() . $file->getClientOriginalName();
                $file->move('images/questions/', $name);
                $data['question_img'] = $name;
            }

            $question = $this->questionRepository->store($data);

            if ($question) {
                return response()->json($question);
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
        try {
            $question = $this->questionRepository->getById($id);

            if ($question) {
                return response()->json($question);
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
            $data = $request->all();

            $question = $this->questionRepository->getById($id);

            if ($file = $request->file('question_img')) {

                $name = 'question_' . time() . $file->getClientOriginalName();

                if ($question->question_img !== null) {
                    unlink(public_path() . '/images/questions/' . $question->question_img);
                }

                $file->move('images/questions/', $name);
                $data['question_img'] = $name;
            }

            $updatedQuestion = $this->questionRepository->update($id, $data);

            if ($updatedQuestion) {
                return response()->json($updatedQuestion);
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
            $question = $this->questionRepository->getById($id);

            if ($question->question_img != null) {
                unlink(public_path() . '/images/questions/' . $question->question_img);
            }

            $deletedQuestion = $this->questionRepository->destroy($id);

            if ($deletedQuestion) {
                return response()->json($id);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
