<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataArray = $this->generateDataArray(10);
        foreach ($dataArray as $key => $value) {
            Vehicle::create([
                'name' => $value['name'],
                'brand' => $value['brand'],
                'police_number' => $value['police_number'],
            ]);
        }
    }

    public function generateUniquePoliceNumber($existingNumbers)
    {
        $letters = range('A', 'Z');
        $numbers = range(0, 9);

        do {
            $number = 'N ';
            for ($i = 0; $i < 4; $i++) {
                $number .= $numbers[array_rand($numbers)];
            }
            $number .= ' ';
            for ($i = 0; $i < 2; $i++) {
                $number .= $letters[array_rand($letters)];
            }
        } while (in_array($number, $existingNumbers));

        return $number;
    }

    public function generateDataArray($numEntries)
    {
        $data = [];
        $existingNumbers = [];

        for ($i = 0; $i < $numEntries; $i++) {
            $policeNumber = $this->generateUniquePoliceNumber($existingNumbers);
            $existingNumbers[] = $policeNumber;

            $names = ['Avanza', 'Xenia', 'Innova', 'Fortuner', 'Ertiga', 'Hiace', 'Grandmax', 'Rush'];
            $brands = ['Toyota', 'Daihatsu', 'Honda', 'Suzuki'];

            $data[] = [
                'name' => $names[array_rand($names)],
                'brand' => $brands[array_rand($brands)],
                'police_number' => $policeNumber
            ];
        }

        return $data;
    }
}
