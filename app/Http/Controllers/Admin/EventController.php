<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;

class EventController extends Controller
{
    //
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $events = $this->eventRepository->get();
        return response()->json($events);
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
            $data = $request->all();

            $event = $this->eventRepository->store($data);

            if ($event) {
                return response()->json($event);
            }
        } catch (Exception $e) {
            return response()->json(['event' => $e->getEvent()], Response::HTTP_FORBIDDEN);
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
            $event = $this->eventRepository->getById($id);

            if ($event) {
                return response()->json($event);
            }
        } catch (Exception $e) {
            return response()->json(['event' => $e->getEvent()], Response::HTTP_FORBIDDEN);
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

            $updatedEvent = $this->eventRepository->update($id, $data);

            if ($updatedEvent) {
                return response()->json($updatedEvent);
            }
        } catch (Exception $e) {
            return response()->json(['event' => $e->getEvent()], Response::HTTP_FORBIDDEN);
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
            $event = $this->eventRepository->destroy($id);

            if ($event) {
                return response()->json($id);
            }
        } catch (Exception $e) {
            return response()->json(['event' => $e->getEvent()], Response::HTTP_FORBIDDEN);
        }
    }
}