<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h3 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h3>Riwayat Absensi Petugas</h3>
    <p>Bulan: <?php echo e(\Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y')); ?></p>

    <table>
        <thead>
            <tr>
                <th>Nama Petugas</th>
                <th>Profesi</th>
                <th>Status</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($absen->petugas->nama); ?></td>
                    <td><?php echo e($absen->petugas->role); ?></td>
                    <td><?php echo e($absen->status); ?></td>
                    <td><?php echo e($absen->checkin_at ? \Carbon\Carbon::parse($absen->checkin_at)->format('d-m-Y H:i') : '-'); ?></td>
                    <td><?php echo e($absen->checkout_at ? \Carbon\Carbon::parse($absen->checkout_at)->format('d-m-Y H:i') : '-'); ?></td>
                    <td>
                        <?php if($absen->latitude && $absen->longitude): ?>
                            <?php echo e($absen->latitude); ?>, <?php echo e($absen->longitude); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/supervisor/riwayat-absensi/pdf.blade.php ENDPATH**/ ?>