<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CsvExportService;

class CsvExportController extends Controller
{
    public function __construct(private CsvExportService $csvExportService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function exportCsv(Request $request)
    {
        return $this->csvExportService->exportCsv($request);
    }
}
