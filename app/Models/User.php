<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'role',
        'password',
        'jabatan',
        'no_telp',
        'job',
        'lokasi',
        'force_change_password',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Relasi: User sebagai supervisor memiliki banyak lokasi kerja
    public function supervisedLocations()
    {
        return $this->hasMany(WorkLocation::class, 'supervisor_id');
    }

    // ✅ Relasi tidak langsung: Supervisor → WorkLocation → Petugas
    public function supervisedPetugas()
    {
        return $this->hasManyThrough(
            Petugas::class,
            WorkLocation::class,
            'supervisor_id',    // Foreign key di WorkLocation
            'work_location_id', // Foreign key di Petugas
            'id',               // Local key di User (supervisor)
            'id'                // Local key di WorkLocation
        );
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
}
