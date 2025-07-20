

<?php $__env->startSection('title', 'Assign Petugas ke Client'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .btn-modal {
        padding: 6px 12px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 8px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 6px;
        width: 80%;
        max-height: 80%;
        overflow-y: auto;
    }

    .close {
        float: right;
        font-size: 24px;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    input[type="text"] {
        padding: 8px;
        width: 100%;
    }
</style>

<h2>Assign Petugas ke Client</h2>

<form action="<?php echo e(route('admin.manage-petugas.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="form-group">
        <label>Client:</label><br>
        <input type="hidden" name="client_id" id="client_id">
        <input type="text" id="client_nama" readonly style="width: 80%; padding: 8px;">
        <button type="button" class="btn-modal" onclick="openModal('modal-client')">Pilih Client</button>
    </div>

    <div class="form-group">
        <label>Petugas:</label><br>
        <input type="hidden" name="petugas_id" id="petugas_id">
        <input type="text" id="petugas_nama" readonly style="width: 80%; padding: 8px;">
        <button type="button" class="btn-modal" onclick="openModal('modal-petugas')">Pilih Petugas</button>
    </div>


    <div class="form-group">
        <label>Lokasi Kerja:</label><br>
        <input type="hidden" name="work_location_id" id="work_location_id">
        <input type="text" id="work_location_nama" readonly style="width: 80%; padding: 8px;">
        <button type="button" class="btn-modal" onclick="openModal('modal-work-location')">Pilih Lokasi</button>
    </div>

    <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none; border-radius: 6px;">
        Simpan
    </button>
</form>

<!-- Modal Client -->
<div id="modal-client" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal-client')">&times;</span>
        <h4>Pilih Client</h4>
        <input type="text" onkeyup="filterTable('table-client', this)" placeholder="Cari client...">
        <table id="table-client">
            <thead>
                <tr>
                    <th>Perusahaan</th>
                    <th>Alamat</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($c->nama_perusahaan); ?></td>
                        <td><?php echo e($c->alamat); ?></td>
                        <td>
                            <form method="GET" action="<?php echo e(route('admin.manage-petugas.create')); ?>">
                                <input type="hidden" name="client_id" value="<?php echo e($c->id); ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Pilih</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Petugas -->
<div id="modal-petugas" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal-petugas')">&times;</span>
        <h4>Pilih Petugas</h4>
        <input type="text" onkeyup="filterTable('table-petugas', this)" placeholder="Cari petugas...">
        <table id="table-petugas">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Profesi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $allPetugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($p->nama); ?></td>
                        <td><?php echo e($p->email); ?></td>
                        <td><?php echo e($p->role); ?></td>
                        <td><button type="button" onclick="selectPetugas(<?php echo e($p->id); ?>, '<?php echo e($p->nama); ?>')">Pilih</button></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>



<!-- Modal Lokasi Kerja -->
<div id="modal-work-location" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal-work-location')">&times;</span>
        <h4>Pilih Lokasi Kerja</h4>
        <?php if(count($workLocations) > 0): ?>
            <input type="text" onkeyup="filterTable('table-location', this)" placeholder="Cari lokasi...">
            <table id="table-location">
                <thead>
                    <tr>
                        <th>Nama Lokasi</th>
                        <th>Alamat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($l->nama_lokasi); ?></td>
                            <td><?php echo e($l->alamat); ?></td>
                            <td>
                                <button type="button" onclick="selectLocation(<?php echo e($l->id); ?>, '<?php echo e($l->nama_lokasi); ?>')">Pilih</button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning mt-2">Silakan pilih client terlebih dahulu.</div>
        <?php endif; ?>
    </div>
</div>

<?php if(request('client_id')): ?>
    <script>
        document.getElementById('client_id').value = "<?php echo e(request('client_id')); ?>";
        document.getElementById('client_nama').value = "<?php echo e(\App\Models\Client::find(request('client_id'))->nama_perusahaan); ?>";
    </script>
<?php endif; ?>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function filterTable(tableId, input) {
        let filter = input.value.toLowerCase();
        let rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    function selectPetugas(id, nama) {
        document.getElementById('petugas_id').value = id;
        document.getElementById('petugas_nama').value = nama;
        closeModal('modal-petugas');
    }

    function selectLocation(id, nama) {
        document.getElementById('work_location_id').value = id;
        document.getElementById('work_location_nama').value = nama;
        closeModal('modal-work-location');
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/manage-petugas/create.blade.php ENDPATH**/ ?>