<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nama_lokasi',
        'latitude',
        'longitude',
        'supervisor_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function petugas()
    {
        return $this->hasMany(Petugas::class);
    }

    public function jadwalTugas()
    {
        return $this->hasMany(JadwalTugas::class);
    }
}
