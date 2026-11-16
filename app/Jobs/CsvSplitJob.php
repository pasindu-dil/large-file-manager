<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CsvSplitJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $inputFilePath, private string $outputDir)
    {
            Log::info("Started job process for {{ $inputFilePath }}");
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chunkLines = config('csv.chunk_lines');

        if (!Storage::exists($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }

        $command = sprintf(
            'cd %s && head -1 %s > header.csv && tail -n +2 %s | split -l %d -d - chunk_ && for f in chunk_*; do cat header.csv "$f" > "${f}.csv" && rm "$f"; done 2>&1',
            escapeshellarg($this->outputDir),
            escapeshellarg($this->inputFilePath),
            escapeshellarg($this->inputFilePath),
            $chunkLines
        );

        exec($command, $output, $exitCode);

        throw_if($exitCode !== 0, \Exception::class, "Error splitting file");

        StreamCsvBatchJob::dispatch($this->outputDir)->onQueue('file-parse');
    }
}
