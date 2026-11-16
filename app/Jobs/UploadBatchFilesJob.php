<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UploadBatchFilesJob implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $file)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $query = "LOAD DATA LOCAL INFILE '{$this->file}' INTO TABLE cell_sites FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES (lac_ci, old_sic, new_site_name, new_sic, district, province, lon, lat, azimuth, cell_name, ci, lac, rac_tac, bsc_rnc_mme_id, cluster_id, technology, vendor, sector_number, sector_id, cluster_owner, mkt_priority, fivegb_category, ds_division, last_available, status);";

        DB::statement($query);
    }
}
