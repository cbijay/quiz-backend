<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\TopicRepository;
use Illuminate\Http\Request;
use App\Services\Admin\TopicService;
use Exception;
use Illuminate\Http\Response;

class TopicController extends Controller
{
    //
    protected $topicService, $topicRepository;

    public function __construct(TopicService $topicService, TopicRepository $topicRepository)
    {
        $this->topicService = $topicService;
        $this->topicRepository = $topicRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $topics = $this->topicService->get();
        return response()->json($topics);
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
            $input['stauts'] = 1;

            $data = [
                'title' => $input['title'],
                'description' => $input['description'],
                'per_q_mark' => $input['per_q_mark'],
                'timer' => $input['timer'],
                'amount' => $request->amount ? $input['amount'] : 0,
                'show_ans' => $input['show_ans'] == true ? 1 : 0,
                'status' => $input['status'],
            ];

            $topic = $this->topicService->store($data);

            if ($topic) {
                return response()->json($topic);
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
            $topic = $this->topicService->getById($id);

            if ($topic) {
                return response()->json($topic);
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
            $topic = $this->topicService->getById($id);

            if (isset($request->show_ans)) {
                $topic->show_ans = 1;
            } else {
                $topic->show_ans = 0;
            }

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'per_q_mark' => $request->per_q_mark,
                'timer' => $request->timer,
                'show_ans' => $request->show_ans == true ? 1 : 0,
                'amount' => $request->amount,
            ];

            $updatedTopic = $this->topicService->update($id, $data);

            if ($updatedTopic) {
                return response()->json($updatedTopic);
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
            $questions = $this->topicRepository->questions($id);

            if (count($questions) > 0) {
                return response()->json(['message' => 'Please delete question from this topic before deleting this topic']);
            } else {
                $topic = $this->topicService->destroy($id);

                if ($topic) {
                    return response()->json($id);
                }
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
