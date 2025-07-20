

<?php $__env->startSection('title', 'Absentra - Manage Petugas'); ?>

<?php $__env->startSection('content'); ?>

<style>
.filter-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.filter-row .button-group {
    display: flex;
    gap: 10px;
}

.filter-row .filter-form {
    display: flex;
    gap: 10px;
}

.filter-select {
    padding: 8px 12px;
    border: 2px solid #ccc;
    border-radius: 6px;
    background: #fff;
    color: #333;
    font-size: 14px;
    transition: border-color 0.3s;
}

.filter-select:focus,
.filter-select:hover {
    border-color: #4a6cf7;
    outline: none;
}

.client-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background-color: #fff;
    margin-top: 10px;
}

.client-table th,
.client-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

.client-table th {
    background-color: #f9f9f9;
    font-weight: bold;
}

.add-button {
    background-color: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    width: 40px;
    height: 40px;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    line-height: 40px;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.add-button:hover {
    background-color: #1e4fc1;
}
</style>

<div class="client-container">
    <h2>Manage Petugas</h2>

    <div class="filter-row">
        <div class="button-group">
            <a href="<?php echo e(route('admin.manage-petugas.create')); ?>" class="add-button" title="Tambah Petugas">+</a>
            <a href="<?php echo e(route('admin.manage-petugas.bulk_upload')); ?>" class="add-button" title="Import Excel" style="background-color: #28a745;">
                <i class="fas fa-file-excel" style="line-height: 40px;"></i>
            </a>
        </div>

        <form method="GET" action="<?php echo e(route('admin.manage-petugas.index')); ?>" class="filter-form">
            <select name="client_id" class="filter-select">
                <option value="">Semua Perusahaan</option>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                        <?php echo e($client->nama_perusahaan); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="role" class="filter-select">
                <option value="">Semua Profesi</option>
                <option value="security" <?php echo e(request('role') == 'security' ? 'selected' : ''); ?>>Security</option>
                <option value="cleaning services" <?php echo e(request('role') == 'cleaning services' ? 'selected' : ''); ?>>Cleaning Services</option>
                <option value="other" <?php echo e(request('role') == 'other' ? 'selected' : ''); ?>>Other</option>
            </select>

            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama/email/NIK" class="filter-select" />

            <button type="submit" class="filter-select">Filter</button>
        </form>

    </div>

    <table class="client-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Perusahaan</th>
                <th>Profesi</th>
                <th>Lokasi</th>
                <th>titik Kerja</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $petugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($p->nama); ?></td>
                    <td><?php echo e($p->client->nama_perusahaan ?? '-'); ?></td>
                    <td><?php echo e(ucfirst($p->role)); ?></td>
                    <td><?php echo e($p->client->alamat ?? '-'); ?></td>
                    <td><?php echo e($p->workLocation->nama_lokasi ?? '-'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.manage-petugas.edit', $p->id)); ?>" title="Pindahkan">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <?php if($p->client_id): ?>
                            <a href="<?php echo e(route('admin.manage-petugas.unassign', $p->id)); ?>"
                                onclick="return confirm('Yakin ingin melepas penugasan petugas ini?')"
                                style="color:#d00; margin-left:10px;" title="Lepas Client">
                                <i class="fas fa-unlink"></i>
                            </a>
                        <?php endif; ?>
                    </td>


                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data petugas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/manage-petugas/index.blade.php ENDPATH**/ ?>