<?php


namespace App\Http\Controllers\supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPatroli;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatLaporanControllerSuper extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan') ?? date('Y-m');
        $nama = $request->get('nama');
        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
        $lokasiSupervisor = auth()->user()->work_location_id;

        $laporan = LaporanPatroli::with('petugas')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('petugas', function ($q) use ($lokasiSupervisor, $nama) {
                $q->where('work_location_id', $lokasiSupervisor);
                if ($nama) {
                    $q->where('nama', 'like', '%' . $nama . '%');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor.riwayat-laporan.index', compact('laporan'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan') ?? date('Y-m');
        $nama = $request->get('nama');
        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
        $lokasiSupervisor = auth()->user()->work_location_id;

        $laporan = LaporanPatroli::with('petugas')
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->whereHas('petugas', function ($q) use ($lokasiSupervisor, $nama) {
                $q->where('work_location_id', $lokasiSupervisor);
                if ($nama) {
                    $q->where('nama', 'like', '%' . $nama . '%');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('supervisor.riwayat-laporan.pdf', [
            'laporan' => $laporan,
            'bulan' => $bulan
        ]);

        return $pdf->stream('riwayat-laporan-patroli.pdf');
    }
}
