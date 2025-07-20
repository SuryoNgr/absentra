<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_tugas_id',
        'petugas_id',
        'status',
        'latitude',
        'longitude',
        'checkin_at',
        'checkout_at',
        'foto_checkin',
    ];

    protected $dates = ['checkin_at', 'checkout_at'];

    public function jadwalTugas()
    {
        return $this->belongsTo(JadwalTugas::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }
}
