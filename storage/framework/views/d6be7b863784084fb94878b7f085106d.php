

<?php $__env->startSection('title', 'Riwayat Laporan Patroli'); ?>

<?php $__env->startSection('content'); ?>
<h4>Riwayat Laporan Patroli</h4>

<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <label>Nama Petugas</label>
            <input type="text" name="nama" class="form-control" value="<?php echo e(request('nama')); ?>" placeholder="Cari nama petugas...">
        </div>
        <div class="col-md-3">
            <label>Bulan</label>
            <input type="month" name="bulan" class="form-control" value="<?php echo e(request('bulan', now()->format('Y-m'))); ?>">
        </div>
        <div class="col-md-5 d-flex align-items-end">
            <button class="btn btn-primary me-2">Filter</button>
            <a href="<?php echo e(route('supervisor.riwayat-laporan.cetak', ['nama' => request('nama'), 'bulan' => request('bulan')])); ?>" class="btn btn-success" target="_blank">Cetak PDF</a>
        </div>
    </div>
</form>

<?php if($laporan->isEmpty()): ?>
    <div class="alert alert-warning">Tidak ada laporan ditemukan.</div>
<?php else: ?>
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Petugas</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i')); ?></td>
                        <td><?php echo e($item->petugas->nama ?? '-'); ?></td>
                        <td><?php echo e($item->deskripsi); ?></td>
                        <td>
                            <?php if($item->foto): ?>
                                <img src="<?php echo e(asset('storage/foto-patroli/' . $item->foto)); ?>" width="100">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.supervisor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/supervisor/riwayat-laporan/index.blade.php ENDPATH**/ ?>