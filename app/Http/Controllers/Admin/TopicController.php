<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Answer;
use App\Repositories\TopicRepository;
use Exception;
use Illuminate\Http\Response;

class TopicController extends Controller
{
    //
    protected $topicRepository, $topicService;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $topics = $this->topicRepository->get();
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

            $topic = $this->topicRepository->store($data);

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
            $topic = $this->topicRepository->getById($id);

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
            $topic = $this->topicRepository->getById($id);

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

            $updatedTopic = $this->topicRepository->update($id, $data);

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
            $topic = $this->topicRepository->destroy($id);

            if ($topic) {
                return response()->json($id);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    public function deleteperquizsheet($id)
    {
        $findanswersheet = Answer::where('topic_id', '=', $id)->get();

        if ($findanswersheet->count() > 0) {
            foreach ($findanswersheet as $value) {
                $value->delete();
            }

            return back()->with('deleted', 'Answer Sheet Deleted For This Quiz !');
        } else {
            return back()->with('added', 'No Answer Sheet Found For This Quiz !');
        }
    }
}