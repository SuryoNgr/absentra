

<?php $__env->startSection('title', 'Bulk Upload Petugas ke Client'); ?>

<?php $__env->startSection('content'); ?>

<h4>Upload Excel untuk Menghubungkan Petugas ke Client & Lokasi Kerja</h4>

<div class="alert alert-info">
    <p>Pastikan format file sesuai dengan kolom berikut:</p>
    <ul>
        <li><strong>email</strong> (wajib)</li>
        <li><strong>nama_perusahaan</strong> (wajib)</li>
        <li><strong>nama_lokasi</strong> (opsional)</li>
    </ul>

    <p>Download template:
        <a href="<?php echo e(asset('templates/template_managePetugas.xlsx')); ?>" target="_blank"
           style="display:inline-block; margin-top:10px; padding:8px 16px; background:#10b981; color:white; border-radius:6px; text-decoration:none;">
           Template Petugas
        </a>
    </p>
</div>

<form action="<?php echo e(route('admin.manage-petugas.importBulk')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
        <label for="file" class="form-label">Pilih File Excel (xlsx / csv)</label>
        <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
    </div>

    <button type="submit" class="btn btn-success">Upload</button>
</form>

<?php if(session('success')): ?>
    <div class="alert alert-success mt-3">
        <?php echo e(session('success')); ?>

        <?php if(session('uploaded_file')): ?>
            <br><small>File: <?php echo e(session('uploaded_file')); ?></small>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/manage-petugas/bulk-upload.blade.php ENDPATH**/ ?>