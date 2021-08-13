<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ReportRepository;
use Exception;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    //
    private $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index($topicId)
    {
        $reports = $this->reportRepository->getReports($topicId);
        return response()->json($reports);
    }

    public function destroy($topicId, $userId)
    {
        try {
            $deleteUserAnswer = $this->reportRepository->deleteUserAnswer($topicId, $userId);

            if ($deleteUserAnswer) {
                return response()->json($userId);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}