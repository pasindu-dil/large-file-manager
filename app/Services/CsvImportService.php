<?php

namespace App\Services;

use App\Jobs\CsvSplitJob;
use App\Jobs\StreamCsvBatchJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

use function Illuminate\Support\defer;

class CsvImportService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function importCsv(object $request)
    {
        throw_if(!$request->hasFile('file'), \Exception::class, 'CSV file not found');

        $fileName = $request->file('file')->hashName();
        $filePath = storage_path('app/private/') . $fileName;

        $outputDirName = uniqid();
        $outputDir  = storage_path("app/private/chunks/{$outputDirName}/");

        defer(fn () => $request->file('file')->move(storage_path('app/private'), $fileName));
        // $request->file('file')->move(storage_path('app/private'), $fileName);

        Log::info("Dispatch job for file: {$fileName}");

        CsvSplitJob::dispatch($filePath, $outputDir)->onQueue('file-parse');

        return response()->json([
            'message' => $outputDirName
        ]);
    }
}
