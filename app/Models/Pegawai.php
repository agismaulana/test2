<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $fillable = ['nama', 'tanggal_masuk', 'total_gaji'];
    protected $guard = [];

    public function kasbon() {
        return $this->hasMany(Kasbon::class, 'pegawai_id', 'id');
    }
}
