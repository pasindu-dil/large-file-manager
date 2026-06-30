<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StreamCsvBatchJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $outputDir) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $files = File::allFiles($this->outputDir);
        $outputDir = $this->outputDir;

        DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
        DB::statement("SET UNIQUE_CHECKS = 0;");

        $jobs = collect($files)
            ->map(fn ($file) => new UploadBatchFilesJob($file->getPathname()))
            ->all();

        Bus::batch($jobs)
            ->name("upload-batch-files-from-{$outputDir}")
            ->allowFailures()
            ->onQueue('file-upload')
            ->finally(fn () => Log::info("Finished job process for {$outputDir}"))
            ->dispatch();
    }
}
