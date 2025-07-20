

<?php $__env->startSection('title', 'Edit Penugasan Petugas'); ?>

<?php $__env->startSection('content'); ?>

<h2>Edit Penugasan Petugas</h2>

<form action="<?php echo e(route('admin.manage-petugas.update', $petugas->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <!-- Dropdown Client -->
    <div class="mb-3">
        <label>Client</label>
        <select name="client_id" class="form-select" required>
            <option value="">-- Pilih Client --</option>
            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($client->id); ?>" <?php echo e($petugas->client_id == $client->id ? 'selected' : ''); ?>>
                    <?php echo e($client->nama_perusahaan); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <!-- Dropdown Lokasi Kerja -->
    <div class="mb-3">
        <label>Lokasi Kerja</label>
        <select name="work_location_id" class="form-select" required>
            <option value="">-- Pilih Lokasi --</option>
            <?php $__currentLoopData = $workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lokasi->id); ?>" <?php echo e($petugas->work_location_id == $lokasi->id ? 'selected' : ''); ?>>
                    <?php echo e($lokasi->nama_lokasi); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/manage-petugas/edit.blade.php ENDPATH**/ ?>