<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalTugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'petugas_id',
        'work_location_id',
        'nama_tim',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi_tugas',
    ];


    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function workLocation()
    {
        return $this->belongsTo(WorkLocation::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function laporanPatroli()
    {
        return $this->hasMany(LaporanPatroli::class);
    }
}
