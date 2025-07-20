

<?php $__env->startSection('title', 'Edit Tugas'); ?>

<?php $__env->startSection('content'); ?>
<h3>Edit Tugas - <?php echo e($tugas->nama_tim); ?></h3>

<form action="<?php echo e(route('admin.jadwal-tugas.update', $tugas->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <input type="hidden" name="client_id" value="<?php echo e($tugas->petugas->client_id); ?>">
    <input type="hidden" name="work_location_id" value="<?php echo e($tugas->work_location_id); ?>">

    <div class="mb-3">
        <label>Nama Tim</label>
        <input type="text" name="nama_tim" class="form-control" value="<?php echo e($tugas->nama_tim); ?>" required>
    </div>

    <div class="mb-3">
        <label>Waktu Mulai</label>
        <input type="datetime-local" name="waktu_mulai" class="form-control" value="<?php echo e(\Carbon\Carbon::parse($tugas->waktu_mulai)->format('Y-m-d\TH:i')); ?>" required>
    </div>

    <div class="mb-3">
        <label>Waktu Selesai</label>
        <input type="datetime-local" name="waktu_selesai" class="form-control" value="<?php echo e(\Carbon\Carbon::parse($tugas->waktu_selesai)->format('Y-m-d\TH:i')); ?>" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi Tugas</label>
        <textarea name="deskripsi_tugas" class="form-control"><?php echo e($tugas->deskripsi_tugas); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Batal</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/jadwal-tugas/edit.blade.php ENDPATH**/ ?>