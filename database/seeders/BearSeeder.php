<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bear;

class BearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bear::truncate();

        $csvFile = fopen(base_path("database/data/beren_locaties.csv"), "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            Bear::create([
                "name" => $data['0'],
                "city" => $data['1'],
                "region" => $data['2'],
                "latitude" => $data['3'],
                "longitude" => $data['4'],
            ]);
        }
        fclose($csvFile);
    }
}
