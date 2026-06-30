<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CsvImportService;
use Illuminate\Http\Request;

class CsvImportController extends Controller
{
    public function __construct(private CsvImportService $csvImportService)
    {}

    /**
     * Show the form for creating a new resource.
     */
    public function importCsv(Request $request)
    {
        $this->csvImportService->importCsv($request);
    }
}
