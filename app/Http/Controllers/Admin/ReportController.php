<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;
use Exception;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    //
    private $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index($topicId)
    {
        $reports = $this->reportService->getReports($topicId);
        return response()->json($reports);
    }

    public function destroy($topicId, $userId)
    {
        try {
            $deleteUserAnswer = $this->reportService->deleteUserAnswer($topicId, $userId);

            if ($deleteUserAnswer) {
                return response()->json($userId);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}