


<style>
    .client-page h1 {
        font-size: 24px;
        margin-bottom: 16px;
        font-weight: 600;
    }

    .client-page a.add-client-btn {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 6px;
        background-color: #f0f0f0;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        border: 1px solid #ccc;
        margin-bottom: 16px;
    }

    .client-page a.add-client-btn:hover {
        background-color: #e4e4e4;
    }

    .client-page table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        background-color: #fff;
    }

    .client-page th,
    .client-page td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .client-page th {
        background-color: #f9f9f9;
        font-weight: bold;
    }

    .client-page .success-message {
        color: green;
        margin-bottom: 12px;
    }

    .client-page tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

<?php $__env->startSection('content'); ?>
<div class="client-page">
    <h2>Daftar Supervisor</h2>

    <?php if(session('success')): ?>
        <div style="color: green; margin-bottom: 15px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <a href="<?php echo e(route('admin.supervisor.create')); ?>" style="display: inline-block; margin-bottom: 15px; padding: 8px 16px; background: #2563eb; color: white; border-radius: 6px; text-decoration: none;">
        + Tambah Supervisor
    </a>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>                
                <th>Total Lokasi</th>
                <th>AKsi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($supervisor->name); ?></td>
                    <td><?php echo e($supervisor->email); ?></td>
                    <td>
                        <?php if($supervisor->supervisedLocations->count() > 0): ?>
                            <span title="<?php echo e($supervisor->supervisedLocations->pluck('nama_lokasi')->implode(', ')); ?>">
                                <?php echo e($supervisor->supervisedLocations->count()); ?>

                            </span>
                        <?php else: ?>
                            0
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <a href="<?php echo e(route('admin.supervisor.edit', $supervisor->id)); ?>" style="margin-right: 10px;">Edit</a>
                        <form action="<?php echo e(route('admin.supervisor.destroy', $supervisor->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus supervisor ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" style="color: red; border: none; background: none; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">Belum ada data supervisor.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/supervisor/index.blade.php ENDPATH**/ ?>