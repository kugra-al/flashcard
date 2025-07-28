<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use Carbon\Carbon;
use App\Helpers\CsvHelper;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = CsvHelper::convertCsv('country-and-continent-codes-list-csv.csv');

        $seeds = [];
        foreach($data as $row) {
            if (!strlen($row['Three_Letter_Country_Code']))
                continue;
            $seeds[] = ([
               'name'           =>  $row['Country_Name'],
               'country_code_2' =>  $row['Two_Letter_Country_Code'],
               'country_code_3' =>  $row['Three_Letter_Country_Code'],
               'flag_url'       =>  strtolower($row['Two_Letter_Country_Code']).'.png',
               'created_at'     =>  Carbon::now()->format('Y-m-d H:i:s'),
               'updated_at'     =>  Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
        if (!Country::count())
            Country::insert($seeds);
    }
}
