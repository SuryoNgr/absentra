<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Petugas;
use App\Models\WorkLocation;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function index(): View
{
    $totalClients = Client::count();
    $totalPetugas = Petugas::count();
    $totalWorkLocations = WorkLocation::count();

    return view('admin.dashboard', compact('totalClients', 'totalPetugas', 'totalWorkLocations'));
}
}
