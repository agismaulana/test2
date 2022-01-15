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

class UnitTest extends TestCase
{
    use WithFaker;
    use WithoutMiddleware;

    /** @test **/
    public function it_store_pegawai() {
        // Random Date
        $dt = $this->faker->dateTimeBetween($startDate='-2 year', $endDate = '-1 year');
        $date = $dt->format('Y-m-d');

        // Get First Name
        $name = $this->faker->name;
        $split = explode(' ', $name);

        // Post Pegawai
        $response = $this->post(route('pegawai.store'), [
            'nama' => $split[0],
            'tanggal_masuk' => $date,
            'total_gaji' => $this->faker->numberBetween($min = 4000000, $max=10000000)
        ]);

        // get callback status
        $response->assertRedirect(route('pegawai.index'));
        $response->assertStatus(302);
    }

    /** @test **/ 
    public function it_get_pegawai() {
        // get pegawai
        $response = $this->get(route('pegawai.index', [1]));
        $jsonContent = $response->getContent();
        echo $response->getContent();
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_store_kasbon() {
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

    /** @test **/
    public function it_get_kasbon() {
        $response = $this->get(route('kasbon.index'));
        $jsonContent = $response->getContent();
        echo $jsonContent;
        $response->assertStatus(200);
    }

    /** @test **/
    public function it_seed_pegawai() {
        $seed = $this->seed(PegawaiSeeder::class);
        $this->assertStatus(200);
    }

    /** @test **/ 
    public function it_seed_kasbon() {
        $seed = $this->seed(KasbonSeeder::class);
        $this->assertStatus(200);
    }

    /** @test **/
    public function it_patch_kasbon() {
        $kasbon = Kasbon::whereNull('tanggal_disetujui')->inRandomOrder()->first();

        $response = $this->patch(route('kasbon.update', $kasbon->id), [
            'id' => $kasbon->id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('kasbon.index'));
    }

    /** @test **/ 
    public function it_post_massal_kasbon() {
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
