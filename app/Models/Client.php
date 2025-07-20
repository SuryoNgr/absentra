<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'latitude',
        'longitude',
    ];

    public function workLocations()
    {
        return $this->hasMany(WorkLocation::class);
    }

    public function petugas()
    {
        return $this->hasMany(Petugas::class);
    }
}
