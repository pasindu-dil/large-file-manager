<?php

return [
    'separator' => ',',
    'enclosure' => '"',
    'escape' => '\\',

    'chunk_lines' => env('CSV_CHUNK_LINES', 100000),
];
