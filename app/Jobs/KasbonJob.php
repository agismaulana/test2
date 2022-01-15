<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Kasbon;

class KasbonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $kasbon;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kasbon)
    {
        $this->kasbon = $kasbon;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $update = Kasbon::where('id', $this->kasbon)->update(['tanggal_disetujui' => date('Y-m-d')]);
    }
}
