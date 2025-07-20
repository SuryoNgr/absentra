

<?php $__env->startSection('title', 'Detail Jadwal - ' . $tanggal); ?>

<style>

    .detail-jadwal-wrapper {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .detail-jadwal-wrapper h3 {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .table-detail-jadwal {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        background-color: white;
    }

    .table-detail-jadwal thead {
        background-color: #e5e7eb;
    }

    .table-detail-jadwal th,
    .table-detail-jadwal td {
        padding: 10px 12px;
        border: 1px solid #ddd;
        text-align: left;
        vertical-align: middle;
    }

    .table-detail-jadwal td {
        background-color: #ffffff;
    }

    .btn-kembali {
        display: inline-block;
        margin-bottom: 20px;
        font-size: 14px;
        color: #2563eb;
        text-decoration: none;
    }

    .btn-kembali:hover {
        text-decoration: underline;
    }

    .no-data-warning {
        padding: 15px;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 6px;
        color: #856404;
        font-size: 14px;
    }
    .form-wrapper {
        background: #f8f9fa;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .form-wrapper h4 {
        margin-bottom: 15px;
        font-size: 18px;
    }

    .form-wrapper label {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }

    .form-wrapper input,
    .form-wrapper textarea,
    .form-wrapper select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .form-wrapper button {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 14px;
    }

    #daftar-petugas-terpilih {
        padding-left: 18px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    #popup-cari-petugas {
        background: white;
        padding: 20px;
        border: 1px solid #ccc;
        position: fixed;
        top: 10%;
        left: 20%;
        width: 60%;
        z-index: 1000;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        border-radius: 8px;
    }

    .btn-sm {
        font-size: 12px;
        padding: 6px 10px;
    }

    .btn-primary {
        background-color: #2563eb;
        color: white;
    }

    .btn-secondary {
        background-color: #ccc;
        color: black;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn + .btn {
        margin-left: 8px;
    }

    .table th,
    .table td {
        font-size: 14px;
        vertical-align: middle;
    }
</style>



<?php $__env->startSection('content'); ?>

<a href="<?php echo e(route('admin.jadwal-tugas.index', [
    'client_id' => $selectedClient,
    'lokasi_id' => $selectedLocation,
    'profesi' => $selectedProfesi,
    'bulan' => $selectedMonth
])); ?>" class="btn btn-secondary mb-3">← Kembali</a>



<h3>Detail Jadwal Tugas - <?php echo e(\Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')); ?></h3>

<div class="detail-jadwal-wrapper">

    <?php if($jadwalTugas->isEmpty()): ?>
        <div class="no-data-warning">Belum ada penugasan untuk tanggal ini.</div>
    <?php else: ?>
        <?php $__currentLoopData = $workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
       <?php if($selectedLocation == $lokasi->id || $selectedLocation == 'all'): ?>
            <h5><?php echo e($lokasi->nama_lokasi); ?></h5>

            <?php if(isset($jadwalTugas[$lokasi->id])): ?>
                <?php $__currentLoopData = $jadwalTugas[$lokasi->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h6><?php echo e($tugas->nama_tim); ?></h6>
                        <p>
                            <?php echo e(\Carbon\Carbon::parse($tugas->waktu_mulai)->translatedFormat('d M Y H:i')); ?> -
                            <?php echo e(\Carbon\Carbon::parse($tugas->waktu_selesai)->translatedFormat('d M Y H:i')); ?>

                        </p>
                        <p><?php echo e(Str::limit($tugas->deskripsi_tugas, 100)); ?></p>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo e($tugas->id); ?>">
                            Detail
                        </button>
                    </div>
                </div>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="modalDetail<?php echo e($tugas->id); ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Penugasan - <?php echo e($tugas->nama_tim); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Deskripsi:</strong> <?php echo e($tugas->deskripsi_tugas); ?></p>
                        <p><strong>Jam Tugas:</strong>
                            <?php echo e(\Carbon\Carbon::parse($tugas->waktu_mulai)->translatedFormat('d M Y H:i')); ?> -
                            <?php echo e(\Carbon\Carbon::parse($tugas->waktu_selesai)->translatedFormat('d M Y H:i')); ?>

                        </p>
                        <p><strong>Petugas:</strong></p>
                        <ul>
                            <?php
                                $tim = \App\Models\JadwalTugas::with('petugas')->where('nama_tim', $tugas->nama_tim)
                                    ->whereDate('waktu_mulai', $tanggal)->get();
                            ?>
                            <?php $__currentLoopData = $tim; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($anggota->petugas->nama); ?> (<?php echo e($anggota->petugas->role); ?>)</li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="<?php echo e(route('admin.jadwal-tugas.edit', $tugas->id)); ?>" class="btn btn-warning">Edit</a>
                        <form method="POST" action="<?php echo e(route('admin.jadwal-tugas.destroy', $tugas->id)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus tugas ini?')">Hapus</button>
                        </form>

                    </div>
                    </div>
                </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="text-muted mb-4">Tidak ada tugas di lokasi ini.</div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>


<button class="btn btn-primary mt-3" onclick="bukaFormPenugasan()">+ Tambah Penugasan</button>



<div id="form-penugasan" class="form-wrapper" style="display: none;">
    <h4>Tambah Penugasan</h4>
    <form method="POST" action="<?php echo e(route('admin.jadwal-tugas.store')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="tanggal" value="<?php echo e($tanggal); ?>">
        <input type="hidden" name="work_location_id" value="<?php echo e($selectedLocation); ?>">
        <div class="mb-3">
            <label>Nama Tim:</label>
            <input type="text" name="nama_tim" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Waktu Mulai:</label>
           <input type="datetime-local" name="waktu_mulai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Waktu Selesai:</label>
            <input type="datetime-local" name="waktu_selesai" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi Tugas:</label>
            <textarea name="deskripsi_tugas" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Petugas Terpilih:</label>
            <ul id="daftar-petugas-terpilih" style="padding-left: 18px;"></ul>
            <div id="input-petugas-wrapper"></div>
            <button type="button" class="btn btn-sm btn-primary mt-2" onclick="bukaPopupCariPetugas()">+ Tambah Petugas</button>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="tutupFormPenugasan()">Batal</button>
    </form>
</div>


<div id="popup-cari-petugas" style="display:none; background:white; padding:20px; border:1px solid #ccc; position:fixed; top:10%; left:20%; width:60%; z-index:1000; box-shadow: 0 0 15px rgba(0,0,0,0.3); border-radius:8px;">
    <h4>Pilih Petugas</h4>
    <input type="text" id="cari-petugas" placeholder="Cari nama/email..." class="form-control mb-2" onkeyup="filterDaftarPetugas()">
    <div id="hasil-cari-petugas" style="max-height:300px; overflow-y:auto;"></div>
    <div class="mt-3">
        <button onclick="tutupPopupPetugas()" class="btn btn-secondary">Tutup</button>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
let daftarSemuaPetugas = [];
let daftarTerpilih = [];

// Ambil dari backend semua ID petugas yang sudah terdaftar di tanggal ini
const petugasSudahTerjadwal = <?php echo json_encode($jadwalTugasSemuaPetugasId, 15, 512) ?>; // <-- dari controller

function bukaFormPenugasan() {
    document.getElementById('form-penugasan').style.display = 'block';
}

function tutupFormPenugasan() {
    document.getElementById('form-penugasan').style.display = 'none';
    daftarTerpilih = [];
    document.getElementById('daftar-petugas-terpilih').innerHTML = '';
    document.getElementById('input-petugas-wrapper').innerHTML = '';
}

function bukaPopupCariPetugas() {
    const clientId = "<?php echo e($selectedClient); ?>";
    const tanggal = "<?php echo e($tanggal); ?>"; // ambil dari controller

    fetch(`/admin/ajax/cari-petugas?client_id=${clientId}&tanggal=${tanggal}`)
        .then(res => res.json())
        .then(data => {
            daftarSemuaPetugas = data;
            tampilkanDaftarPetugas(data);
            document.getElementById('popup-cari-petugas').style.display = 'block';
        });
}


function tampilkanDaftarPetugas(data) {
    let html = '<ul class="list-group">';
    data.forEach(p => {
        if (petugasSudahTerjadwal.includes(p.id)) return; // Skip yang sudah terjadwal

        html += `<li class="list-group-item">
            <label>
                <input type="checkbox" value="${p.id}" onchange="togglePetugas(this, '${p.nama}')" ${daftarTerpilih.includes(p.id.toString()) ? 'checked' : ''}>
                ${p.nama} (${p.role})
            </label>
        </li>`;
    });
    html += '</ul>';
    document.getElementById('hasil-cari-petugas').innerHTML = html;
}


function filterDaftarPetugas() {
    const keyword = document.getElementById('cari-petugas').value.toLowerCase();
    const hasil = daftarSemuaPetugas.filter(p => p.nama.toLowerCase().includes(keyword) || (p.email ?? '').toLowerCase().includes(keyword));
    tampilkanDaftarPetugas(hasil);
}

function togglePetugas(checkbox, nama) {
    const id = checkbox.value;
    const list = document.getElementById('daftar-petugas-terpilih');
    const wrapper = document.getElementById('input-petugas-wrapper');

    if (checkbox.checked) {
        if (!daftarTerpilih.includes(id)) {
            daftarTerpilih.push(id);
            const li = document.createElement('li');
            li.textContent = nama;
            li.setAttribute('data-id', id);
            list.appendChild(li);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'petugas_ids[]';
            input.value = id;
            input.setAttribute('data-id', id);
            wrapper.appendChild(input);
        }
    } else {
        daftarTerpilih = daftarTerpilih.filter(val => val !== id);
        const item = list.querySelector(`li[data-id='${id}']`);
        if (item) item.remove();
        const input = wrapper.querySelector(`input[data-id='${id}']`);
        if (input) input.remove();
    }
}

function tutupPopupPetugas() {
    document.getElementById('popup-cari-petugas').style.display = 'none';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/jadwal-tugas/show.blade.php ENDPATH**/ ?>