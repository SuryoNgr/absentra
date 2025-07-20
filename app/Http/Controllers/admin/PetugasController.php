<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\SimpleExcel\SimpleExcelReader;
class PetugasController extends Controller
{
   public function index(Request $request)
    {
        $roles = Petugas::select('role')->distinct()->pluck('role');
        $query = Petugas::query();

        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        $petugas = $query->get();

        return view('admin.petugas.index', compact('petugas', 'roles'));
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
             'role' => 'required|in:security,cleaning services,other',
            'email' => 'required|email|unique:petugas,email',
        ]);

        Petugas::create([
            'nama'          => $request->nama,
            'nik'           => $request->nik,
            'role'          => $request->role,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_hp'      => $request->nomor_hp,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password'      => Hash::make('password123'), // default password
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function edit(Petugas $petugas)
    {
        return view('admin.petugas.edit', compact('petugas'));
    }

    public function update(Request $request, Petugas $petugas)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'role' => 'required|in:security,cleaning services,other',
            'email' => 'required|email|unique:petugas,email,' . $petugas->id,
        ]);

        $petugas->update([
            'nama'          => $request->nama,
            'role'          => $request->role,
            'nik'           => $request->nik,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_hp'      => $request->nomor_hp,
            'email'         => $request->email,
            'alamat'        => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil diperbarui');
    }

    public function destroy(Petugas $petugas)
    {
        $petugas->delete();
        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus');
    }

    public function showBulkUploadForm()
        {
            return view('admin.petugas.bulk-upload');
        }

    public function importBulk(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();

        // Membaca file, gunakan path() agar tidak error ".tmp"
        $rows = SimpleExcelReader::create($file->path(), 'xlsx')->getRows();

        $count = 0;
        foreach ($rows as $row) {
            // Kolom wajib
            if (empty($row['nama']) || empty($row['email']) || empty($row['role'])) {
                continue;
            }

            // Konversi tanggal lahir
            $tanggal_lahir = null;
            if (!empty($row['tanggal_lahir'])) {
                if ($row['tanggal_lahir'] instanceof \DateTimeInterface) {
                    $tanggal_lahir = $row['tanggal_lahir']->format('Y-m-d');
                } elseif (is_string($row['tanggal_lahir'])) {
                    $timestamp = strtotime($row['tanggal_lahir']);
                    if ($timestamp) {
                        $tanggal_lahir = date('Y-m-d', $timestamp);
                    }
                }
            }

            // Cek apakah email sudah ada -> update
            $petugas = Petugas::where('email', $row['email'])->first();
            if ($petugas) {
                $petugas->update([
                    'nik'           => $row['nik'] ?? $petugas->nik,
                    'nama'          => $row['nama'],
                    'role'          => $row['role'],
                    'tempat_lahir'  => $row['tempat_lahir'] ?? $petugas->tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? $petugas->jenis_kelamin,
                    'nomor_hp'      => $row['nomor_hp'] ?? $petugas->nomor_hp,
                    'alamat'        => $row['alamat'] ?? $petugas->alamat,
                ]);
            } else {
                Petugas::create([
                    'nik'           => $row['nik'] ?? null,
                    'nama'          => $row['nama'],
                    'email'         => $row['email'],
                    'role'          => $row['role'],
                    'tempat_lahir'  => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $tanggal_lahir,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'nomor_hp'      => $row['nomor_hp'] ?? null,
                    'alamat'        => $row['alamat'] ?? null,
                    'password'      => Hash::make('password123'), // default password
                ]);
            }

            $count++;
        }

        return redirect()->back()->with([
            'success' => "$count petugas berhasil diimport.",
            'uploaded_file' => $fileName,
        ]);
    }

    
}
