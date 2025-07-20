<?php

namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\User;


class SupervisorPetugasController extends Controller
{
    public function index()
    {
        $supervisorLocationId = auth()->user()->work_location_id;

        $petugas = Petugas::where('work_location_id', $supervisorLocationId)->get();

        return view('supervisor.petugas.index', compact('petugas'));
    }
}
