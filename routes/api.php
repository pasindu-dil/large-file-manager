<?php

use App\Http\Controllers\API\CsvExportController;
use App\Http\Controllers\API\CsvImportController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/'
], function () {
    Route::post('import/csv', [CsvImportController::class, 'importCsv']);
    Route::get('export/csv', [CsvExportController::class, 'exportCsv']);
});
