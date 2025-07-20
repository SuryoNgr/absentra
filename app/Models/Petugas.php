<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Petugas extends Model
{
    use HasFactory;
     
    protected $table = 'petugas';

    protected $fillable = [
    'client_id',
    'work_location_id',
    'nama',
    'nik', 
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'nomor_hp',
    'email',
    'alamat',
    'role',
    'password',
];


    protected $hidden = ['password'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function workLocation()
    {
        return $this->belongsTo(WorkLocation::class);
    }

    public function jadwalTugas()
    {
        return $this->hasMany(JadwalTugas::class);
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
