<?php

namespace App\Helpers;

use Storage;

class CsvHelper
{
    // Converts a .csv named $path found in storage/app/private to an associative array
    public static function convertCsv($path) {
        $csv = Storage::disk('local')->get($path);
        $lines = array_filter(array_map('trim', explode(PHP_EOL, $csv)));
        $rows = array_map('str_getcsv', $lines);
        $headers = array_shift($rows);
        $data =
            array_map(function ($row) use ($headers)
                {
                    $row = array_pad($row, count($headers), null);
                    return array_combine($headers, $row);
                },
            $rows);
        return $data;
    }
}
