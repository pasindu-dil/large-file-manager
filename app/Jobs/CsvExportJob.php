<?php

namespace App\Jobs;

use App\Models\CellSite;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\directoryExists;

class CsvExportJob implements ShouldQueue
{
    use Queueable, Dispatchable, Batchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $filePath)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $offset = 0;
        $limit = 10000;
        $time = time();

        if (!directoryExists($this->filePath)) {
            mkdir($this->filePath, 0777, true);
        }

        $query = $this->buildQuery($offset, $limit);

        do {
            $fileName = $time . '_' . uniqid() . '_export.csv';
            DB::statement("{$query->toSql()} INTO OUTFILE '{$this->filePath}/{$fileName}' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n';", $query->getBindings());

            $offset += $limit;
            $query = $this->buildQuery($offset, $limit);
        } while ($query->count() > 0);
    }

    private function buildQuery(int $offset, int $limit)
    {
        return CellSite::select([
            'lac_ci',
            'old_sic',
            'new_site_name',
            'new_sic',
        ])->offset($offset)->limit($limit);
    }
}
