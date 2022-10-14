<?php

namespace Database\Seeders;

use App\Models\Venue\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Airport::truncate();

        $csvFile = fopen(public_path("/data/airports.csv"), "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if ($data[2] === 'large_airport' || $data[2] === 'medium_airport') {
                Airport::create([
                    "priority" => $data[2] === 'large_airport' ? 4 : 1,
                    "ident" => $data[1],
                    "name" => $data[3],
                    "lat" => $data[4],
                    "lng" => $data[5],
                    "country_code" => $data[9],
                    "municipalty" => $data[10],
                    "iata_code" => $data[13] ? $data[13] : $data[12] ?? null,
                ]);
            }
        }
        fclose($csvFile);
    }
}
