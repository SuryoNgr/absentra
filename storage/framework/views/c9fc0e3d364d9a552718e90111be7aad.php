

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

    .client-page select {
        padding: 6px 10px;
        font-size: 14px;
        margin-bottom: 16px;
    }
    .add-petugas-btn {
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
</style>

<?php $__env->startSection('content'); ?>
<div class="client-page">
    <h2>Daftar Petugas</h2>

    <?php if(session('success')): ?>
        <div class="success-message">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <div style="display: flex; gap: 10px;">
            <a href="<?php echo e(route('admin.petugas.create')); ?>" class="add-petugas-btn" style="background: #2563eb; color: white;">
                +
            </a>
            <a href="<?php echo e(route('admin.petugas.bulk-upload.form')); ?>" class="add-petugas-btn" style="background: #28a745; color: white;">
               <i class="fas fa-file-excel" style="line-height: 40px;"></i>
            </a>
        </div>
        <form>
            <select id="filterRole" onchange="filterPetugas()">
                <option value="">Semua Role</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role); ?>" <?php echo e(request('role') == $role ? 'selected' : ''); ?>>
                        <?php echo e(ucfirst($role)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Profesi</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $petugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($p->nama); ?></td>
                    <td><?php echo e($p->nik ?? '-'); ?></td>
                    <td><?php echo e(ucfirst($p->role)); ?></td>
                    <td><?php echo e($p->email ?? '-'); ?></td>
                    <td><?php echo e($p->nomor_hp ?? '-'); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.petugas.edit', $p)); ?>" title="Edit" style="margin-right: 10px;">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a href="<?php echo e(route('admin.petugas.destroy', $p->id)); ?>"
                        onclick="return confirm('Yakin ingin menghapus petugas ini?')"
                        title="Hapus" style="color: red;">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">Belum ada data petugas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function filterPetugas() {
        let role = document.getElementById('filterRole').value;
        let url = new URL(window.location.href);
        if (role) {
            url.searchParams.set('role', role);
        } else {
            url.searchParams.delete('role');
        }
        window.location.href = url.toString();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/petugas/index.blade.php ENDPATH**/ ?>