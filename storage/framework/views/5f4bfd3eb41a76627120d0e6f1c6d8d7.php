

<style>
    .tooltip-hover {
    position: relative;
    cursor: pointer;
    color: #007bff;
}

.tooltip-box {
    display: none;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 8px;
    z-index: 10;
    top: 20px;
    left: 0;
    font-size: 13px;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 6px;
}

.tooltip-hover:hover .tooltip-box {
    display: block;
}
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

    .client-page .action-buttons a,
    .client-page .action-buttons form {
        display: inline-block;
        margin-right: 8px;
    }

    .client-page .action-buttons button {
        background: none;
        border: none;
        color: red;
        cursor: pointer;
        padding: 0;
    }
</style>

<?php $__env->startSection('content'); ?>
<div class="client-page">
    <h1>Daftar Client</h1>

    <?php if(session('success')): ?>
        <div class="success-message">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <a href="<?php echo e(route('admin.clients.create')); ?>" class="add-client-btn">+ Tambah Client</a>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Perusahaan</th>
                <th>Alamat</th>
               <th>Total Petugas</th>
                <th>Total Titik Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($client->nama_perusahaan); ?></td>
                    <td>
                        <?php if($client->latitude && $client->longitude): ?>
                            <a href="https://www.google.com/maps?q=<?php echo e($client->latitude); ?>,<?php echo e($client->longitude); ?>" target="_blank">
                                <?php echo e($client->alamat); ?>

                            </a>
                        <?php else: ?>
                            <?php echo e($client->alamat); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($client->petugas_count); ?></td>
                   <td>
                        <span class="tooltip-hover">
                            <?php echo e($client->work_locations_count); ?>

                            <div class="tooltip-box">
                                <?php $__currentLoopData = $client->workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    • <?php echo e($lokasi->nama_lokasi); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </span>
                    </td>
                    <td class="action-buttons">
                        <a href="<?php echo e(route('admin.clients.edit', $client->id)); ?>">Edit</a>
                        <form action="<?php echo e(route('admin.clients.destroy', $client->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus client ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">Belum ada data client.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/clients/index.blade.php ENDPATH**/ ?>