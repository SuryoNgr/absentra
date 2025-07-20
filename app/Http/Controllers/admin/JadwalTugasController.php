<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\WorkLocation;
use App\Models\Petugas;
use App\Models\JadwalTugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;

class JadwalTugasController extends Controller
{
   public function index(Request $request)
    {
        $clients = Client::all();
        $client_id = $request->get('client_id');
        $lokasi_id = $request->get('lokasi_id');
        $profesi = $request->get('profesi');

        $bulan = $request->get('bulan') ?? date('Y-m');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $hariDalamBulan = [];
        for ($tanggal = $tanggalAwal->copy(); $tanggal->lte($tanggalAkhir); $tanggal->addDay()) {
            $hariDalamBulan[] = $tanggal->format('Y-m-d');
        }

        $workLocations = $client_id ? WorkLocation::where('client_id', $client_id)->get() : collect();

        $jadwalTugas = JadwalTugas::with('petugas')
            ->when($client_id, fn($q) => $q->whereHas('workLocation', fn($q) => $q->where('client_id', $client_id)))
            ->when($lokasi_id, fn($q) => $q->where('work_location_id', $lokasi_id))
            ->when($profesi, fn($q) => $q->whereHas('petugas', fn($q) => $q->where('role', $profesi)))
            ->whereBetween('waktu_mulai', [$tanggalAwal, $tanggalAkhir])
            ->get();


        $petugas = Petugas::when($client_id, function ($query) use ($client_id) {
            return $query->where('client_id', $client_id);
        })->get();

        return view('admin.jadwal-tugas.index', [
            'clients' => $clients,
            'workLocations' => $workLocations,
            'jadwalTugas' => $jadwalTugas,
            'calendarDays' => $hariDalamBulan,
            'selectedClient' => $client_id,
            'selectedLocation' => $lokasi_id,
            'selectedMonth' => $bulan,
            'petugas' => $petugas,
            'selectedProfesi' => $profesi,

        ]);
    }


    public function store(Request $request)
{
    $request->validate([
        'nama_tim'         => 'required|string|max:255',
        'deskripsi_tugas'  => 'nullable|string',
        'waktu_mulai'      => 'required|date',
        'waktu_selesai'    => 'required|date|after:waktu_mulai',
        'work_location_id' => 'required|exists:work_locations,id',
        'petugas_ids'      => 'required|array|min:1',
        'petugas_ids.*'    => 'exists:petugas,id',
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->petugas_ids as $petugas_id) {
            JadwalTugas::create([
                'petugas_id'       => $petugas_id,
                'work_location_id' => $request->work_location_id,
                'nama_tim'         => $request->nama_tim,
                'waktu_mulai'      => $request->waktu_mulai,
                'waktu_selesai'    => $request->waktu_selesai,
                'deskripsi_tugas'  => $request->deskripsi_tugas,
            ]);
            Petugas::where('id', $petugas_id)->update([
            'work_location_id' => $request->work_location_id,
        ]);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
    }
}


public function show(Request $request, $tanggal)
{
    $clientId = $request->client_id;
    $lokasiId = $request->lokasi_id;
    $profesi = $request->profesi ?? '';
    $bulan = $request->bulan ?? date('Y-m');
    $tanggalFormatted = Carbon::parse($tanggal)->format('Y-m-d');

    // Ambil semua lokasi kerja milik client
    $workLocations = WorkLocation::where('client_id', $clientId)->get();

    // Ambil semua tugas pada tanggal & client tersebut
    $query = JadwalTugas::with('petugas')
        ->whereDate('waktu_mulai', $tanggalFormatted)
        ->whereHas('petugas', function($q) use ($clientId) {
            $q->where('client_id', $clientId);
        });

    if ($lokasiId !== 'all') {
        $query->where('work_location_id', $lokasiId);
    }

    $jadwalTugas = $query->get();

    $jadwalTugasSemuaPetugasId = $jadwalTugas->pluck('petugas_id')->unique()->toArray();

    return view('admin.jadwal-tugas.show', [
        'tanggal' => $tanggalFormatted,
        'jadwalTugas' => $jadwalTugas->groupBy('work_location_id'),
        'selectedClient' => $clientId,
        'selectedLocation' => $lokasiId,
        'selectedProfesi' => $profesi,
        'selectedMonth' => $bulan,
        'workLocations' => $workLocations,
        'jadwalTugasSemuaPetugasId' => $jadwalTugasSemuaPetugasId,
    ]);
}





public function cariPetugas(Request $request)
{
    $clientId = $request->get('client_id');
    $tanggal = $request->get('tanggal'); // contoh: 2025-07-19

    // Ambil semua petugas milik client
    $petugasQuery = Petugas::query()->where('client_id', $clientId);

    // Jika ada tanggal, filter hanya petugas yang belum ditugaskan di tanggal itu
    if ($tanggal) {
        $tanggalMulai = Carbon::parse($tanggal)->startOfDay();
        $tanggalSelesai = Carbon::parse($tanggal)->endOfDay();

        $sudahTerjadwal = JadwalTugas::whereBetween('waktu_mulai', [$tanggalMulai, $tanggalSelesai])
            ->pluck('petugas_id')
            ->toArray();

        $petugasQuery->whereNotIn('id', $sudahTerjadwal);
    }

    $petugas = $petugasQuery->select('id', 'nama', 'role', 'email')->get();

    return response()->json($petugas);
}

public function edit($id)
{
    $tugas = JadwalTugas::with('petugas')->findOrFail($id);
    $tanggal = \Carbon\Carbon::parse($tugas->waktu_mulai)->toDateString();
    $workLocation = WorkLocation::find($tugas->work_location_id);
    $clientId = $workLocation->client_id;

    // Ambil petugas yang tersedia dari client yang sama
    $petugasList = Petugas::where('client_id', $clientId)->get();

    return view('admin.jadwal-tugas.edit', compact('tugas', 'tanggal', 'petugasList'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_tim' => 'required',
        'waktu_mulai' => 'required|date',
        'waktu_selesai' => 'required|date|after:waktu_mulai',
        'deskripsi_tugas' => 'nullable|string',
    ]);

    $tugas = JadwalTugas::findOrFail($id);
    $tugas->update([
        'nama_tim' => $request->nama_tim,
        'waktu_mulai' => $request->waktu_mulai,
        'waktu_selesai' => $request->waktu_selesai,
        'deskripsi_tugas' => $request->deskripsi_tugas,
    ]);

    return redirect()->route('admin.jadwal-tugas.show', [
        'tanggal' => \Carbon\Carbon::parse($request->waktu_mulai)->toDateString(),
        'client_id' => $request->client_id,
        'lokasi_id' => $request->work_location_id,
    ])->with('success', 'Tugas berhasil diperbarui.');
}

public function destroy($id)
{
    $tugas = JadwalTugas::findOrFail($id);
    $tanggal = \Carbon\Carbon::parse($tugas->waktu_mulai)->toDateString();
    $lokasiId = $tugas->work_location_id;
    $clientId = $tugas->workLocation->client_id;

    $tugas->delete();

    return redirect()->route('admin.jadwal-tugas.show', [
        'tanggal' => $tanggal,
        'client_id' => $clientId,
        'lokasi_id' => $lokasiId,
    ])->with('success', 'Tugas berhasil dihapus.');
}

public function uploadForm()
{
    $clients = Client::all();
    return view('admin.jadwal-tugas.upload', compact('clients'));
}

public function handleUpload(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $clientId = $request->client_id;
    $file = $request->file('file')->store('uploads');

    $rows = SimpleExcelReader::create(storage_path("app/{$file}"))->getRows();

    foreach ($rows as $row) {
        $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
        $namaTim = $row['nama_tim'];
        $waktuMulai = "{$tanggal} {$row['waktu_mulai']}";
        $waktuSelesai = "{$tanggal} {$row['waktu_selesai']}";
        $namaPetugas = $row['nama_petugas'];
        $lokasiNama = $row['lokasi_kerja'];
        $deskripsi = $row['deskripsi_tugas'] ?? '-';

        $petugas = Petugas::where('client_id', $clientId)
            ->where('nama', $namaPetugas)
            ->first();

        $lokasi = WorkLocation::where('client_id', $clientId)
            ->where('nama_lokasi', $lokasiNama)
            ->first();

        if (!$petugas || !$lokasi) continue;

        JadwalTugas::create([
            'petugas_id' => $petugas->id,
            'nama_tim' => $namaTim,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'deskripsi_tugas' => $deskripsi,
            'work_location_id' => $lokasi->id,
        ]);
    }

    return redirect()->route('admin.jadwal-tugas.index', [
        'client_id' => $clientId,
        'bulan' => Carbon::now()->format('Y-m'),
    ])->with('success', 'Jadwal berhasil diunggah.');
}


}
