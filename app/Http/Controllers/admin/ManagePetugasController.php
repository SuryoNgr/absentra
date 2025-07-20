<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\WorkLocation;
use App\Models\Client;

class ManagePetugasController extends Controller
{
   public function index(Request $request)
    {
        $query = Petugas::with('client')->whereNotNull('client_id');

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by nama, email, atau NIK
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('nik', 'like', "%$search%");
            });
        }

        $petugas = $query->orderBy('nama')->paginate(10); // 10 per halaman
        $clients = Client::all();

        return view('admin.manage-petugas.index', compact('petugas', 'clients'));
    }
    public function create(Request $request)
    {
        $clients = Client::all();
        $allPetugas = Petugas::whereNull('client_id')->get(); // filter sesuai kebutuhanmu
        $workLocations = [];

        if ($request->has('client_id')) {
            $workLocations = WorkLocation::where('client_id', $request->client_id)->get();
        }

        return view('admin.manage-petugas.create', compact('clients', 'allPetugas', 'workLocations'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'petugas_id' => 'required|exists:petugas,id',
            'client_id' => 'required|exists:clients,id',
        ]);

        $petugas = Petugas::findOrFail($request->petugas_id);
        $petugas->client_id = $request->client_id;
        $petugas->save();

        return redirect()->route('admin.manage-petugas.index')->with('success', 'Petugas berhasil ditugaskan ke client.');
    }

    public function edit($id)
    {
        $petugas = Petugas::with(['client', 'workLocation'])->findOrFail($id);
        $clients = Client::orderBy('nama_perusahaan')->get();
        $workLocations = WorkLocation::where('client_id', $petugas->client_id)->get();

        return view('admin.manage-petugas.edit', compact('petugas', 'clients', 'workLocations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'work_location_id' => 'required|exists:work_locations,id',
        ]);

        $petugas = Petugas::findOrFail($id);
        $petugas->client_id = $request->client_id;
        $petugas->work_location_id = $request->work_location_id;
        $petugas->save();

        return redirect()->route('admin.manage-petugas.index')->with('success', 'Penugasan berhasil diperbarui.');
    }

public function unassign($id)
{
    $petugas = Petugas::findOrFail($id);
    $petugas->client_id = null;
    $petugas->save();

    return redirect()->route('admin.manage-petugas.index')->with('success', 'Penugasan petugas berhasil dilepas.');
}
public function showBulkUpload()
        {
            return view('admin.manage-petugas.bulk-upload');
        }

   public function importBulk(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv|max:2048',
    ]);

    $file = $request->file('file');
    $fileName = $file->getClientOriginalName();

    $rows = SimpleExcelReader::create($file->path(), 'xlsx')->getRows();
    $count = 0;

    foreach ($rows as $row) {
        // Wajib: email dan nama perusahaan
        if (empty($row['email']) || empty($row['nama_perusahaan'])) {
            continue;
        }

        // Cari petugas berdasarkan email
        $petugas = Petugas::where('email', $row['email'])->first();

        // Cari client berdasarkan nama perusahaan
        $client = Client::where('nama_perusahaan', $row['nama_perusahaan'])->first();

        // Jika salah satu tidak ditemukan, lewati
        if (!$petugas || !$client) {
            continue;
        }

        // Update client_id petugas
        $petugas->client_id = $client->id;
        $petugas->save();

        $count++;
    }

    return redirect()->back()->with([
        'success' => "$count petugas berhasil dihubungkan ke client.",
        'uploaded_file' => $fileName,
    ]);
}



}