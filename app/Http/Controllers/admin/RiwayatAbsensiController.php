<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Client;
use App\Models\Petugas;
use Carbon\Carbon;
use Barryvdh\DomPDF\PDF;

class RiwayatAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::all();
        $clientId = $request->get('client_id');
        $role = $request->get('role');
        $bulan = $request->get('bulan') ?? date('Y-m');

        $tanggalAwal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $absensi = Absensi::with(['petugas', 'jadwalTugas'])
            ->when($clientId, function ($query) use ($clientId) {
                $query->whereHas('petugas', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('petugas', function ($q) use ($role) {
                    $q->where('role', $role);
                });
            })
            ->whereBetween('checkin_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('checkin_at', 'desc')
            ->get();

        return view('admin.riwayat-absensi.index', [
            'absensis' => $absensi,
            'clients' => $clients,
            'selectedClient' => $clientId,
            'selectedRole' => $role,
            'selectedMonth' => $bulan,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $clientId = $request->get('client_id');
        $role = $request->get('role');
        $bulan = $request->get('bulan') ?? date('Y-m');

        $tanggalAwal = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
        $tanggalAkhir = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();

        $absensis = \App\Models\Absensi::with(['petugas', 'jadwalTugas'])
            ->when($clientId, function ($query) use ($clientId) {
                $query->whereHas('petugas', function ($q) use ($clientId) {
                    $q->where('client_id', $clientId);
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('petugas', function ($q) use ($role) {
                    $q->where('role', $role);
                });
            })
            ->whereBetween('checkin_at', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('checkin_at', 'desc')
            ->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.riwayat-absensi.pdf', [
            'absensis' => $absensis,
            'bulan' => $bulan,
        ])->setPaper('a4', 'landscape');


        return $pdf->download("riwayat-absensi-{$bulan}.pdf");
    }

}
