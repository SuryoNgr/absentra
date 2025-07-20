

<?php $__env->startSection('title', 'Penjadwalan Tugas'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .filter-section {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }

    .calendar-cell {
        padding: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        font-size: 14px;
    }

    .calendar-cell:hover {
        background-color: #e2e8f0;
    }
</style>

<h2>Penjadwalan Petugas</h2>


<form method="GET" action="<?php echo e(route('admin.jadwal-tugas.index')); ?>" class="mb-4">
    <label for="client-select">Pilih Client:</label>
    <div class="d-flex align-items-center" style="gap:10px;">
        <select name="client_id" id="client-select" required class="form-select" style="max-width:300px;">
            <option value="">-- Pilih Perusahaan --</option>
            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($client->id); ?>" <?php echo e($selectedClient == $client->id ? 'selected' : ''); ?>>
                    <?php echo e($client->nama_perusahaan); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button type="submit" class="btn btn-primary">Terapkan</button>
    </div>
</form>


<?php if($selectedClient): ?>
    <form method="GET" action="<?php echo e(route('admin.jadwal-tugas.index')); ?>">
        <input type="hidden" name="client_id" value="<?php echo e($selectedClient); ?>">

        <div class="filter-section">
            <select name="lokasi_id" id="lokasi-select">
                <option value="">Semua Lokasi Kerja</option>
                <?php $__currentLoopData = $workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($loc->id); ?>" <?php echo e($selectedLocation == $loc->id ? 'selected' : ''); ?>>
                        <?php echo e($loc->nama_lokasi); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="profesi">
                <option value="">Semua Profesi</option>
                <option value="security" <?php echo e($selectedProfesi == 'security' ? 'selected' : ''); ?>>Security</option>
                <option value="cleaning services" <?php echo e($selectedProfesi == 'cleaning services' ? 'selected' : ''); ?>>Cleaning Services</option>
                <option value="other" <?php echo e($selectedProfesi == 'other' ? 'selected' : ''); ?>>Other</option>
            </select>

            <input type="month" name="bulan" value="<?php echo e($selectedMonth ?? date('Y-m')); ?>">

            <button type="submit">Filter</button>
        </div>
    </form>

    
    <div class="calendar-grid">
        <?php $__currentLoopData = $calendarDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="calendar-cell" onclick="bukaDetail('<?php echo e($day); ?>')">
                <strong><?php echo e(\Carbon\Carbon::parse($day)->translatedFormat('d M')); ?></strong>
                <div style="margin-top:5px;">
                    <?php $__currentLoopData = $jadwalTugas->where('waktu_mulai', '>=', $day . ' 00:00:00')->where('waktu_mulai', '<=', $day . ' 23:59:59'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="font-size: 12px; margin-top: 3px;">
                            🧍 <?php echo e($task->petugas->nama); ?><br>
                            ⏰ <?php echo e(\Carbon\Carbon::parse($task->waktu_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($task->waktu_selesai)->format('H:i')); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<script>
    function bukaDetail(tanggal) {
        const params = new URLSearchParams(window.location.search);
        const client = params.get('client_id') || '';
        const profesi = params.get('profesi') || '';
        const bulan = params.get('bulan') || '<?php echo e(date('Y-m')); ?>';

        let lokasi = params.get('lokasi_id') || '';
        if (lokasi === '') {
            lokasi = 'all';
        }

        window.location.href = `/admin/jadwal-tugas/${tanggal}?client_id=${client}&lokasi_id=${lokasi}&profesi=${profesi}&bulan=${bulan}`;
    }


    // Load lokasi dari AJAX (jika diperlukan, opsional)
    document.getElementById('client-select')?.addEventListener('change', function () {
        const selectedClient = this.value;
        if (!selectedClient) return;

        // Optional: bisa pakai AJAX atau langsung submit form client saja.
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/jadwal-tugas/index.blade.php ENDPATH**/ ?>