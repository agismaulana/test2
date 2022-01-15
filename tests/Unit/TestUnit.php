<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// Models
use App\Models\Pegawai;
use App\Models\Kasbon;

// Seeder
use Database\Seeders\KasbonSeeder;
use Database\Seeders\PegawaiSeeder;

class TestUnit extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /**@test**/
    public static function it_store_pegawai() {
        // Post Pegawai
        $response = $this->post(route('pegawai.store'), [
            'nama' => $this->faker->word(10),
            'tanggal_masuk' => $this->faker->date('Y-m-d'),
            'total_gaji' => $this->faker->numberBetween($min = 4000000, $max=10000000)
        ]);

        // get callback status
        $response->assertRedirect(route('pegawai.index'));
        $response->assertStatus(302);
    }

    /**@test**/ 
    public static function it_get_pegawai() {
        // get pegawai
        $response = $this->get(route('pegawai.index'));
        $jsonContent = $response->response()->getContent();
        $response->assertStatus(200);
    }

    /**@test**/
    public static function it_store_kasbon() {
        // get Data
        $individualPegawai = Pegawai::inRandomOrder()->first();
        $cekKasbon = Kasbon::where('pegawai_id', $individualPegawai->id)->get();
        
        // get Total Kasbon
        $arrKasbon = [];
        for($i = 0; $i < count($cekKasbon); $i++) {
            array_push($arrKasbon, $cekKasbon[$i]->total_kasbon);
        }
        $totalKasbon = array_sum($arrKasbon);
        
        // to send pengajuan
        $total_pengajuan = $this->faker->numberBetween($min=0, $max=$individualPegawai->total_gaji * 0.5);

        // get date pegawai in office after 1 year
        $date = $individualPegawai->tanggal_masuk;
        $date = strtotime($date);
        $new_date = strtotime('+ 1 year', $date);

        // post to kasbon
        if(($date - $new_date) > 0) {
            if($cekKasbon <= 3) {
                if(($total_pengajuan + $total_kasbon) < $individualPegawai->total_gaji) {
                    $response = $this->post(route('kasbon.store'), [
                        'tanggal_diajukan' => date('Y-m-d'),
                        'tanggal_disetujui' => null,
                        'pegawai_id' => $individualPegawai->id,
                        'total_kasbon' => $total_pengajuan,
                    ]);

                    $response->assertStatus(302);
                    $response->assertRedirect(route('kasbon.index'));
                } else {
                    $this->assertFalse(true);
                }
            } else {
                $this->assertFalse(true);
            }
        } else {
            $this->assertFalse(true);
        }
    }

    /**@test**/
    public static function it_get_kasbon() {
        $response = $this->get(route('kasbon.index'));
        $jsonContent = $response->response()->getContent();
        echo $jsonContent;
        $response->assertStatus(200);
    }

    /**@test**/
    public function it_seed_pegawai() {
        $seed = $this->seed(PegawaiSeeder::class);
        $this->assertStatus(200);
    }

    /**@test**/ 
    public static function it_seed_kasbon() {
        $seed = $this->seed(KasbonSeeder::class);
        $this->assertStatus(200);
    }

    /**@test**/
    public static function it_patch_kasbon() {
        $kasbon = Kasbon::whereNull('tanggal_disetujui')->inRandomOrder()->first();

        $response = $this->patch(route('kasbon.update'), [
            'id' => $kasbon->id
        ]);

        $response->assertStatus(201);
        $response->assertRedirect(route('kasbon.index'));
    }


    public static function it_post_massal_kasbon() {
        $kasbon = Kasbon::whereNull('tanggal_disetujui')->get();
        $arrKasbon = [];

        for($i = 0; $i < count($kasbon); $i++) {
            array_push($arrKasbon, $kasbon[$i]->id);
        }

        $response = $this->post(route('kasbon.update_massal'), [
            'id' => $arrKasbon
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('kasbon.index'));
    }
}
