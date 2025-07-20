<?php

use Illuminate\Support\Facades\Route;
use App\Models\WorkLocation;
use App\Models\Petugas;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ClientController;
use App\Http\Controllers\admin\SupervisorController;
use App\Http\Controllers\admin\PetugasController;
use App\Http\Controllers\admin\ManagePetugasController;
use App\Http\Controllers\admin\JadwalTugasController;
use App\Http\Controllers\admin\RiwayatAbsensiController;
use App\Http\Controllers\admin\RiwayatLaporanController;
use App\Http\Controllers\supervisor\SupervisorPetugasController;
use App\Http\Controllers\supervisor\RiwayatAbsensiControllerSuper;
use App\Http\Controllers\supervisor\RiwayatLaporanControllerSuper;

// Halaman Login (GET)
Route::get('/', [AuthController::class, 'index'])->name('login');

// Proses Login (POST)
Route::post('/verify', [AuthController::class, 'verify'])->name('auth.verify');

// Logout (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Admin
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/home', [AdminController::class, 'index'])->name('admin.dashboard');

    //client routes
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('admin.clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('admin.clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('admin.clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('admin.clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');

     // Supervisor Routes
    Route::get('/supervisor', [SupervisorController::class, 'index'])->name('admin.supervisor.index');
    Route::get('/supervisor/create', [SupervisorController::class, 'create'])->name('admin.supervisor.create');
    Route::post('/supervisor', [SupervisorController::class, 'store'])->name('admin.supervisor.store');
    Route::get('/supervisor/{id}/edit', [SupervisorController::class, 'edit'])->name('admin.supervisor.edit');
    Route::put('/supervisor/{id}', [SupervisorController::class, 'update'])->name('admin.supervisor.update');
    Route::delete('/supervisor/{id}', [SupervisorController::class, 'destroy'])->name('admin.supervisor.destroy');

    //Petugas Routes
    Route::get('/petugas', [PetugasController::class, 'index'])->name('admin.petugas.index');
    Route::get('/petugas/create', [PetugasController::class, 'create'])->name('admin.petugas.create');
    Route::post('/petugas', [PetugasController::class, 'store'])->name('admin.petugas.store');
    Route::get('/petugas/{petugas}/edit', [PetugasController::class, 'edit'])->name('admin.petugas.edit');
    Route::put('/petugas/{petugas}', [PetugasController::class, 'update'])->name('admin.petugas.update');
    Route::delete('/petugas/{petugas}', [PetugasController::class, 'destroy'])->name('admin.petugas.destroy');
    Route::get('/petugas/bulk-upload', [PetugasController::class, 'showBulkUploadForm'])->name('admin.petugas.bulk-upload.form');
    Route::post('/petugas/bulk-upload', [PetugasController::class, 'importBulk'])->name('admin.petugas.bulk-upload.import');

    // Manage Petugas Routes
    Route::get('/manage-petugas', [ManagePetugasController::class, 'index'])->name('admin.manage-petugas.index');
    Route::get('/manage-petugas/create', [ManagePetugasController::class, 'create'])->name('admin.manage-petugas.create');
    Route::post('/manage-petugas', [ManagePetugasController::class, 'store'])->name('admin.manage-petugas.store');
    Route::get('/manage-petugas/{id}/edit', [ManagePetugasController::class, 'edit'])->name('admin.manage-petugas.edit');
    Route::put('/manage-petugas/{id}', [ManagePetugasController::class, 'update'])->name('admin.manage-petugas.update');
    Route::get('manage-petugas/{id}/unassign', [ManagePetugasController::class, 'unassign'])->name('admin.manage-petugas.unassign');
    Route::get('/manage-petugas/bulk-upload', [ManagePetugasController::class, 'showBulkUpload'])->name('admin.manage-petugas.bulk_upload');
    Route::post('/manage-petugas/bulk-upload', [ManagePetugasController::class, 'importBulk'])->name('admin.manage-petugas.importBulk');
    Route::get('/manage-petugas/template', [ManagePetugasController::class, 'downloadTemplate'])->name('admin.manage-petugas.template');
    
    
    Route::get('/jadwal-tugas', [JadwalTugasController::class, 'index'])->name('admin.jadwal-tugas.index');
    Route::post('/jadwal-tugas', [JadwalTugasController::class, 'store'])->name('admin.jadwal-tugas.store');
    Route::get('/jadwal-tugas/{tanggal}', [JadwalTugasController::class, 'show'])->name('admin.jadwal-tugas.show');
    Route::get('jadwal-tugas/{id}/edit', [JadwalTugasController::class, 'edit'])->name('admin.jadwal-tugas.edit');
    Route::put('jadwal-tugas/{id}', [JadwalTugasController::class, 'update'])->name('admin.jadwal-tugas.update');
    Route::delete('jadwal-tugas/{id}', [JadwalTugasController::class, 'destroy'])->name('admin.jadwal-tugas.destroy');
    Route::get('/jadwal-tugas/upload', [JadwalTugasController::class, 'uploadForm'])->name('admin.jadwal-tugas.upload-form');
    Route::post('/jadwal-tugas/upload', [JadwalTugasController::class, 'handleUpload'])->name('admin.jadwal-tugas.upload');


    Route::get('/ajax/work-locations', function (\Illuminate\Http\Request $request) {
    $clientId = $request->get('client_id');
    $locations = \App\Models\WorkLocation::where('client_id', $clientId)->get(['id', 'nama_lokasi']);

    return response()->json($locations);    
    })->name('admin.ajax.work-locations');

    
    Route::get('/ajax/cari-petugas', function (Request $request) {
    try {
        $client_id = $request->get('client_id');

        if (!$client_id) {
            return response()->json([], 200);
        }

        $petugas = Petugas::where('client_id', $client_id)
            ->select('id', 'nama', 'role', 'email')
            ->orderBy('nama')
            ->get();

        return response()->json($petugas);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


 Route::get('/riwayat-absensi', [RiwayatAbsensiController::class, 'index'])->name('admin.riwayat-absensi.index');
 Route::get('/riwayat-absensi/export-pdf', [RiwayatAbsensiController::class, 'exportPdf'])->name('admin.riwayat-absensi.export-pdf');

 
Route::get('/riwayat-laporan', [RiwayatLaporanController::class, 'index'])->name('admin.riwayat-laporan.index');
    Route::get('/riwayat-laporan/cetak', [RiwayatLaporanController::class, 'cetak'])->name('admin.riwayat-laporan.cetak');

});

Route::prefix('supervisor')->middleware(['auth', 'isSupervisor'])->group(function () {
 Route::get('/petugas', [SupervisorPetugasController::class, 'index'])->name('supervisor.petugas.index');

Route::get('/riwayat-absensi', [RiwayatAbsensiControllerSuper::class, 'index'])->name('supervisor.riwayat-absensi.index');
Route::get('/riwayat-absensi/export-pdf', [RiwayatAbsensiControllerSuper::class, 'exportPdf'])->name('supervisor.riwayat-absensi.export-pdf');

Route::get('/riwayat-laporan', [RiwayatLaporanControllerSuper::class, 'index'])->name('supervisor.riwayat-laporan.index');
Route::get('/riwayat-laporan/cetak', [RiwayatLaporanControllerSuper::class, 'exportPdf'])->name('supervisor.riwayat-laporan.cetak');

});
