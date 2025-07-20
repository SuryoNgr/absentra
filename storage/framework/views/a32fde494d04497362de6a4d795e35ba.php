

<?php $__env->startSection('title', 'Riwayat Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-4">Riwayat Absensi Petugas</h3>

    <a href="<?php echo e(route('supervisor.riwayat-absensi.export-pdf', [
        'role' => $selectedRole,
        'bulan' => $selectedMonth,
    ])); ?>" target="_blank" class="btn btn-sm btn-danger mb-3">
        Export PDF
    </a>

    
    <form method="GET" action="<?php echo e(route('supervisor.riwayat-absensi.index')); ?>" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="role" class="form-label">Profesi</label>
            <select name="role" id="role" class="form-select">
                <option value="">-- Semua Profesi --</option>
                <option value="security" <?php echo e(request('role') == 'security' ? 'selected' : ''); ?>>Security</option>
                <option value="cleaning services" <?php echo e(request('role') == 'cleaning services' ? 'selected' : ''); ?>>Cleaning Services</option>
                <option value="other" <?php echo e(request('role') == 'other' ? 'selected' : ''); ?>>Other</option>
            </select>
        </div>

        <div class="col-md-4">
            <label for="bulan" class="form-label">Bulan</label>
            <input type="month" name="bulan" id="bulan" class="form-control" value="<?php echo e(request('bulan', now()->format('Y-m'))); ?>">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm">
            <thead class="table-light">
                <tr>
                    <th>Nama Petugas</th>
                    <th>Profesi</th>
                    <th>Status</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Lokasi</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($absen->petugas->nama); ?></td>
                        <td><?php echo e($absen->petugas->role); ?></td>
                        <td>
                            <?php if($absen->status == 'checkin'): ?> <span class="badge bg-success">Check-in</span>
                            <?php elseif($absen->status == 'terlambat checkin'): ?> <span class="badge bg-warning text-dark">Terlambat</span>
                            <?php elseif($absen->status == 'lupa checkout'): ?> <span class="badge bg-danger">Lupa Checkout</span>
                            <?php else: ?> <span class="badge bg-secondary">Tidak Masuk</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($absen->checkin_at ? \Carbon\Carbon::parse($absen->checkin_at)->format('d M Y H:i') : '-'); ?></td>
                        <td><?php echo e($absen->checkout_at ? \Carbon\Carbon::parse($absen->checkout_at)->format('d M Y H:i') : '-'); ?></td>
                        <td>
                            <?php if($absen->latitude && $absen->longitude): ?>
                                <a href="https://www.google.com/maps?q=<?php echo e($absen->latitude); ?>,<?php echo e($absen->longitude); ?>" target="_blank">
                                    Lihat Lokasi
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($absen->foto_checkin): ?>
                                <a href="<?php echo e(asset('storage/foto-absensi/' . $absen->foto_checkin)); ?>" target="_blank">
                                    <img src="<?php echo e(asset('storage/foto-absensi/' . $absen->foto_checkin)); ?>" alt="Foto" width="50">
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data absensi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.supervisor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/supervisor/riwayat-absensi/index.blade.php ENDPATH**/ ?>