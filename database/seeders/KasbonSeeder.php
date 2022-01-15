<?php

namespace Database\Seeders;

use App\Models\Kasbon;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KasbonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $pegawai = Pegawai::all();
        $arr_id = [];
        $arr_data = [];

        for($i = 0; $i < count($pegawai); $i++) {
            array_push($arr_id, $pegawai[$i]->id);
            array_push($arr_id, $pegawai[$i]->id);
            array_push($arr_id, $pegawai[$i]->id);
            if(count($arr_id) > 100) {
                for($j = 0; $j < count($arr_id); $j++) {
                    // Random Date
                    $dt = $faker->dateTimeBetween($startDate='-2 month', $endDate = '-1month');
                    $date = $dt->format('Y-m-d');

                    $individualPegawai = Pegawai::findOrFail($arr_id[$j]);
                    $total_pengajuan = $faker->numberBetween($min = 0, $max=$individualPegawai->total_gaji * 0.5);

                    $data = [
                        'tanggal_diajukan' => $date,
                        'tanggal_disetujui' => null,
                        'pegawai_id' => $arr_id[$j],
                        'total_kasbon' => $total_pengajuan,
                    ];

                    Kasbon::create($data);
                }
                return false;
            }
        }
    }
}
