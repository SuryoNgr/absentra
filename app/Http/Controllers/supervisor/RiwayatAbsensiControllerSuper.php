<?php
namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatAbsensiControllerSuper extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan') ?? date('Y-m');
        $role = $request->get('role'); // ✅ tambahkan ini jika ingin pakai filter role
        $namaPetugas = $request->get('nama');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $workLocationId = auth()->user()->work_location_id;

        $absensi = Absensi::with(['petugas', 'jadwalTugas'])
            ->whereHas('petugas', function ($query) use ($workLocationId, $namaPetugas, $role) {
                $query->where('work_location_id', $workLocationId);

                if ($namaPetugas) {
                    $query->where('nama', 'like', '%' . $namaPetugas . '%');
                }

                if ($role) {
                    $query->where('role', $role);
                }
            })
            ->whereBetween('checkin_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('checkin_at', 'desc')
            ->get();

        return view('supervisor.riwayat-absensi.index', [
            'absensis' => $absensi,
            'selectedRole' => $role,
            'selectedMonth' => $bulan,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan') ?? date('Y-m');
        $role = $request->get('role');
        $namaPetugas = $request->get('nama');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
        $workLocationId = auth()->user()->work_location_id;

        $absensi = Absensi::with(['petugas', 'jadwalTugas'])
            ->whereHas('petugas', function ($query) use ($workLocationId, $namaPetugas, $role) {
                $query->where('work_location_id', $workLocationId);

                if ($namaPetugas) {
                    $query->where('nama', 'like', '%' . $namaPetugas . '%');
                }

                if ($role) {
                    $query->where('role', $role);
                }
            })
            ->whereBetween('checkin_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('checkin_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('supervisor.riwayat-absensi.pdf', [
            'absensis' => $absensi,
            'selectedMonth' => $bulan,
        ]);

        return $pdf->stream('riwayat-absensi-supervisor.pdf');
    }
}
