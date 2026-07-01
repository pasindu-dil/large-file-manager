<?php

namespace App\Services;

use App\Jobs\CsvExportJob;
use App\Models\CellSite;

class CsvExportService
{
    public function exportCsv(object $request)
    {
        CsvExportJob::dispatch("/var/lib/mysql-files");
    }

    private function query()
    {
        return CellSite::select([
            'lac_ci',
            'old_sic',
            'new_site_name',
            'new_sic',
        ]);
    }
}
