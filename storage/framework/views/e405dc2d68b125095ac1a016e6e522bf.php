<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Patroli</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { max-width: 100px; }
    </style>
</head>
<body>
    <h3>Riwayat Laporan Patroli</h3>
    <p>Bulan: <?php echo e(\Carbon\Carbon::parse($bulan)->translatedFormat('F Y')); ?></p>

    <table>
        <thead>
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
                    <td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i')); ?></td>
                    <td><?php echo e($item->petugas->nama ?? '-'); ?></td>
                    <td><?php echo e($item->deskripsi); ?></td>
                    <td>
                        <?php if($item->foto): ?>
                            <img src="<?php echo e(public_path('storage/foto-patroli/' . $item->foto)); ?>" alt="foto">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/supervisor/riwayat-laporan/pdf.blade.php ENDPATH**/ ?>