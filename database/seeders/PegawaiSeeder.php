<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i = 1; $i <= 45; $i++) {
            // Random Date
            $dt = $faker->dateTimeBetween($startDate='-3 year', $endDate = '-2 year');
            $date = $dt->format('Y-m-d');

            // Get First Name
            $name = $faker->name;
            $split = explode(' ', $name);

            // Data To Send 
            $data = [
                'nama' => $split[0],
                'tanggal_masuk' => $date,
                'total_gaji' => $faker->numberBetween(4000000, 10000000)
            ];

            Pegawai::create($data);
        }
    }
}
