

<?php $__env->startSection('content'); ?>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <?php echo e(auth('web')->user()->name); ?>!</p>

    <div style="margin-top: 30px;margin-left: 20px;">
        <ul>
            <li><strong>Total Client:</strong> <?php echo e($totalClients); ?></li>
            <li><strong>Total Petugas:</strong> <?php echo e($totalPetugas); ?></li>
            <li><strong>Total Lokasi Kerja:</strong> <?php echo e($totalWorkLocations); ?></li>
        </ul>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>