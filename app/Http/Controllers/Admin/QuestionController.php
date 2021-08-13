<?php

namespace App\Http\Controllers\Admin;

use App\Events\ActiveQuestion;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
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
        $questions = $this->questionRepository->askedQuestion();

        return response()->json($questions);
    }

    public function allQuestions()
    {
        $topics = $this->questionService->get();

        return response()->json($topics);
    }

    public function updateStatus($topicId, $id, $status)
    {
        try {
            $updateStatus = $this->questionRepository->updateStatus($id, $status);

            if ($updateStatus) {
                $questions = $this->questionRepository->getByTopics($topicId);
                return response()->json($questions);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function openQuestion($id, $status)
    {
        try {
            $question = $this->questionRepository->openQuestion($id, $status);

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
            $question = $this->questionRepository->resetTimer($id, $status);

            if ($question) {
                return response()->json($question);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function topics()
    {
        $topics = $this->questionRepository->getTopics();

        return response()->json($topics);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicId)
    {
        $questions = $this->questionRepository->getByTopics($topicId);

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
                $excel = $this->questionRepository->importExcel($topicId, $file);
            }

            if ($excel) {
                return response()->json($excel);
            }
        } catch (Exception $e) {
            return response()->json(['message' => "Error while uploading data..please check the format"], Response::HTTP_FORBIDDEN);
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

            $question = $this->questionService->store($data);

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
            $question = $this->questionService->getById($id);

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
    public function update($id, Request $request)
    {
        try {
            $data = $request->all();

            $question = $this->questionService->getById($id);

            $file = $request->file('question_img');

            if ($file) {

                $name = 'question_' . time() . $file->getClientOriginalName();

                if ($question->question_img !== null) {
                    unlink(public_path() . '/images/questions/' . $question->question_img);
                }

                $file->move('images/questions/', $name);
                $data['question_img'] = $name;
            }

            $updatedQuestion = $this->questionService->update($id, $data);

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
            $answers = $this->questionRepository->answers($id);

            if (count($answers) > 0) {
                return response()->json(['message' => 'Please reset user answers from student report before deleting this question']);
            } else {

                $deletedQuestion = $this->questionService->destroy($id);

                if ($deletedQuestion) {
                    return response()->json($id);
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
