<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;

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
}