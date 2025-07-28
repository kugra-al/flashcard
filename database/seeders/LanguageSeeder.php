<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Country;
use Carbon\Carbon;
use App\Helpers\CsvHelper;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = CsvHelper::convertCsv('languages.csv');
        $languageNames = CsvHelper::convertCsv('language_names.csv');
        $countries = Country::select('id', 'name', 'country_code_3')->get();
        $tmp = [];

        foreach($countries as $country) {
            $tmp[$country->country_code_3] = ([
                'id'    => $country->id,
                'name'  => $country->name
            ]);
        }
        $countries = $tmp;
        $names = [];
        foreach($languageNames as $languageName) {
            $name = explode(',', $languageName['name'])[0];
            if (strlen($languageName['639-1']))
                $names[$languageName['639-1']] = $name;
            if (strlen($languageName['639-2']))
                $names[$languageName['639-2']] = $name;
            if (strlen($languageName['639-2/B']))
                $names[$languageName['639-2/B']] = $name;
        }

        $seeds = [];
        foreach($languages as $row) {
            if (!isset($countries[$row['ISO3']]))
                continue;
            $country = $countries[$row['ISO3']];
            foreach(explode(',', $row['Languages']) as $language) {

                if (strpos($language, '-') !== false && isset($names[strtolower(explode('-', $language)[1])]))
                    $name = $country['name'].' '.$names[strtolower(explode('-', $language)[1])];
                else {
                    if (isset($names[$language]))
                        $name = $names[$language];
                }
                if (strlen($name)) {
                    $seeds[] = ([
                        'name'          => $name,
                        'iso-639'       => $language,
                        'country_id'    => $country['id'],
                        'created_at'     =>  Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at'     =>  Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        if (!Language::count())
            Language::insert($seeds);
    }
}
