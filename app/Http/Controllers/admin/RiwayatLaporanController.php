<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPatroli;
use App\Models\Petugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RiwayatLaporanController extends Controller
{
    public function index(Request $request)
    {
        $nama = $request->get('nama');
        $bulan = $request->get('bulan') ?? date('Y-m');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $laporan = LaporanPatroli::with('petugas')
            ->when($nama, function ($query) use ($nama) {
                $query->whereHas('petugas', function ($q) use ($nama) {
                    $q->where('nama', 'like', "%$nama%");
                });
            })
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.riwayat-laporan.index', [
            'laporan' => $laporan,
            'selectedNama' => $nama,
            'selectedMonth' => $bulan,
        ]);
    }

    public function cetak(Request $request)
    {
        $nama = $request->get('nama');
        $bulan = $request->get('bulan') ?? date('Y-m');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $laporan = LaporanPatroli::with('petugas')
            ->when($nama, function ($query) use ($nama) {
                $query->whereHas('petugas', function ($q) use ($nama) {
                    $q->where('nama', 'like', "%$nama%");
                });
            })
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.riwayat-laporan.pdf', [
            'laporan' => $laporan,
            'bulan' => $bulan,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('riwayat-laporan-' . $bulan . '.pdf');
    }
}