<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\WorkLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
{
   $clients = Client::withCount('petugas', 'workLocations')
                 ->with('workLocations') // <-- tambahkan ini
                 ->get();

    return view('admin.clients.index', compact('clients'));
}


    public function create()
    {
        $supervisors = \App\Models\User::where('role', 'supervisor')->get();
        return view('admin.clients.create', compact('supervisors'));
    }



public function store(Request $request)
{
    $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'work_locations.*.nama_lokasi' => 'required|string|max:255',
        'work_locations.*.latitude' => 'required|numeric',
        'work_locations.*.longitude' => 'required|numeric',
        'work_locations.*.supervisor_id' => 'required|exists:users,id',
    ]);

    DB::beginTransaction();

    try {
        $client = Client::create([
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        foreach ($request->work_locations as $lokasi) {
            WorkLocation::create([
                'client_id' => $client->id,
                'nama_lokasi' => $lokasi['nama_lokasi'],
                'latitude' => $lokasi['latitude'],
                'longitude' => $lokasi['longitude'],
                'supervisor_id' => $lokasi['supervisor_id'],
            ]);
        }

        DB::commit();
        return redirect()->route('admin.clients.index')->with('success', 'Client berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Gagal menyimpan data client: ' . $e->getMessage());
    }
}

public function edit($id)
{
 $client = Client::with('workLocations')->findOrFail($id);
$supervisors = User::where('role', 'supervisor')->get();

return view('admin.clients.edit', compact('client', 'supervisors'));

}

    public function update(Request $request, Client $client)
{
    $validated = $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'work_locations' => 'nullable|array',
        'work_locations.*.nama_lokasi' => 'required|string|max:255',
        'work_locations.*.latitude' => 'required|numeric',
        'work_locations.*.longitude' => 'required|numeric',
        'work_locations.*.supervisor_id' => 'required|exists:users,id',
    ]);

    // Update data utama client
    $client->update([
        'nama_perusahaan' => $validated['nama_perusahaan'],
        'alamat' => $validated['alamat'],
        'latitude' => $validated['latitude'],
        'longitude' => $validated['longitude'],
    ]);

    // Hapus semua lokasi kerja lama
    $client->workLocations()->delete();

    // Simpan ulang semua lokasi kerja baru (jika ada)
    if (!empty($validated['work_locations'])) {
        foreach ($validated['work_locations'] as $lokasi) {
            $client->workLocations()->create([
                'nama_lokasi' => $lokasi['nama_lokasi'],
                'latitude' => $lokasi['latitude'],
                'longitude' => $lokasi['longitude'],
                'supervisor_id' => $lokasi['supervisor_id'],
            ]);
        }
    }

    return redirect()->route('admin.clients.index')->with('success', 'Client berhasil diperbarui.');
}

public function destroy(Client $client)
{
    // Hapus relasi lokasi kerja jika diperlukan (jika tidak ada cascade)
    $client->workLocations()->delete();

    // Hapus petugas jika diperlukan
    $client->petugas()->delete();

    // Hapus client
    $client->delete();

    return redirect()->route('admin.clients.index')->with('success', 'Client berhasil dihapus.');
}



}
