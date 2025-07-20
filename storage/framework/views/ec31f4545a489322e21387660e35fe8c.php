

<?php $__env->startSection('content'); ?>
    <h2>Edit Client</h2>

    <?php if($errors->any()): ?>
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>- <?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.clients.update', $client->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="margin-bottom: 20px;">
            <label>Nama Perusahaan:</label><br>
            <input type="text" name="nama_perusahaan" value="<?php echo e($client->nama_perusahaan); ?>" required style="width: 100%; padding: 10px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label>Alamat:</label><br>
            <textarea name="alamat" required style="width: 100%; padding: 10px;"><?php echo e($client->alamat); ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label>Lokasi Utama Perusahaan:</label>
            <div id="mainMap" style="height: 300px; margin-top: 10px;"></div>
            <input type="hidden" name="latitude" id="mainLat" value="<?php echo e($client->latitude); ?>">
            <input type="hidden" name="longitude" id="mainLng" value="<?php echo e($client->longitude); ?>">
            <button type="button" onclick="resetMainMarker()" style="margin-top: 10px;">Reset Lokasi</button>
        </div>

        <hr>
        <h3>Daftar Lokasi Kerja</h3>
        <div id="work-location-container">
            <?php $__currentLoopData = $client->workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="location-box-<?php echo e($i); ?>" style="margin-top: 30px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9;">
                    <label>Nama Lokasi Kerja:</label><br>
                    <input type="text" name="work_locations[<?php echo e($i); ?>][nama_lokasi]" value="<?php echo e($lokasi->nama_lokasi); ?>" required style="width: 100%; padding: 8px;">

                    <label style="margin-top:10px; display:block;">Supervisor:</label>
                    <select name="work_locations[<?php echo e($i); ?>][supervisor_id]" required style="width: 100%; padding: 8px;">
                        <option value="">Pilih Supervisor</option>
                        <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supervisor->id); ?>" <?php echo e($lokasi->supervisor_id == $supervisor->id ? 'selected' : ''); ?>>
                                <?php echo e($supervisor->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div id="map-<?php echo e($i); ?>" style="height: 250px; margin: 10px 0;"></div>
                    <input type="hidden" name="work_locations[<?php echo e($i); ?>][latitude]" id="lat-<?php echo e($i); ?>" value="<?php echo e($lokasi->latitude); ?>">
                    <input type="hidden" name="work_locations[<?php echo e($i); ?>][longitude]" id="lng-<?php echo e($i); ?>" value="<?php echo e($lokasi->longitude); ?>">
                    <button type="button" onclick="resetLocation(<?php echo e($i); ?>)">Reset Lokasi</button>
                    <button type="button" onclick="removeWorkLocation(<?php echo e($i); ?>)" style="color: red;">Hapus Lokasi</button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button type="button" onclick="addWorkLocation()">+ Tambah Titik Lokasi Kerja</button>

        <br><br>
        <button type="submit" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 6px;">Update Client</button>
    </form>

    <!-- Leaflet & Geocoder -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // Lokasi Utama
        let mainMap = L.map('mainMap').setView(
    [<?php echo e($client->latitude ?? -6.2088); ?>, <?php echo e($client->longitude ?? 106.8456); ?>],
    13
);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mainMap);
L.Control.geocoder().addTo(mainMap);

let mainMarker = L.marker(
    [<?php echo e($client->latitude ?? -6.2088); ?>, <?php echo e($client->longitude ?? 106.8456); ?>],
    { draggable: true }
).addTo(mainMap);

mainMarker.on('dragend', function(e) {
    const pos = e.target.getLatLng();
    document.getElementById('mainLat').value = pos.lat;
    document.getElementById('mainLng').value = pos.lng;
});

mainMap.on('click', function(e) {
    mainMarker.setLatLng(e.latlng);
    document.getElementById('mainLat').value = e.latlng.lat;
    document.getElementById('mainLng').value = e.latlng.lng;
});

function resetMainMarker() {
    const defaultLatLng = [-6.2088, 106.8456];
    mainMarker.setLatLng(defaultLatLng);
    mainMap.setView(defaultLatLng, 13);
    document.getElementById('mainLat').value = defaultLatLng[0];
    document.getElementById('mainLng').value = defaultLatLng[1];
}


        // Lokasi kerja existing
        <?php $__currentLoopData = $client->workLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            let map<?php echo e($i); ?> = L.map('map-<?php echo e($i); ?>').setView([<?php echo e($lokasi->latitude); ?>, <?php echo e($lokasi->longitude); ?>], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map<?php echo e($i); ?>);
            L.Control.geocoder().addTo(map<?php echo e($i); ?>);
            let marker<?php echo e($i); ?> = L.marker([<?php echo e($lokasi->latitude); ?>, <?php echo e($lokasi->longitude); ?>], { draggable: true }).addTo(map<?php echo e($i); ?>);
            marker<?php echo e($i); ?>.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                document.getElementById('lat-<?php echo e($i); ?>').value = pos.lat;
                document.getElementById('lng-<?php echo e($i); ?>').value = pos.lng;
            });
            map<?php echo e($i); ?>.on('click', function(e) {
                marker<?php echo e($i); ?>.setLatLng(e.latlng);
                document.getElementById('lat-<?php echo e($i); ?>').value = e.latlng.lat;
                document.getElementById('lng-<?php echo e($i); ?>').value = e.latlng.lng;
            });
            window['map_<?php echo e($i); ?>'] = map<?php echo e($i); ?>;
            window['marker_<?php echo e($i); ?>'] = marker<?php echo e($i); ?>;
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        let locationIndex = <?php echo e(count($client->workLocations)); ?>;

      function addWorkLocation() {
    const container = document.getElementById('work-location-container');
    const currentIndex = locationIndex;
    const div = document.createElement('div');
    div.id = `location-box-${currentIndex}`;
    div.style = 'margin-top: 30px; border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9;';
    div.innerHTML = `
        <label>Nama Lokasi Kerja:</label><br>
        <input type="text" name="work_locations[${currentIndex}][nama_lokasi]" required style="width: 100%; padding: 8px;">

        <label style="margin-top:10px; display:block;">Supervisor:</label>
        <select name="work_locations[${currentIndex}][supervisor_id]" required style="width: 100%; padding: 8px;">
            <option value="">Pilih Supervisor</option>
            <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($supervisor->id); ?>"><?php echo e($supervisor->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <div id="map-${currentIndex}" style="height: 250px; margin: 10px 0;"></div>
        <input type="hidden" name="work_locations[${currentIndex}][latitude]" id="lat-${currentIndex}">
        <input type="hidden" name="work_locations[${currentIndex}][longitude]" id="lng-${currentIndex}">
        <button type="button" onclick="resetLocation(${currentIndex})">Reset Lokasi</button>
        <button type="button" onclick="removeWorkLocation(${currentIndex})" style="color: red;">Hapus Lokasi</button>
    `;
    container.appendChild(div);

    // Beri jeda agar div map siap di-render
    setTimeout(() => {
        const defaultPos = [-6.2088, 106.8456];
        const map = L.map(`map-${currentIndex}`).setView(defaultPos, 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.Control.geocoder().addTo(map);

        const marker = L.marker(defaultPos, { draggable: true }).addTo(map);
        document.getElementById(`lat-${currentIndex}`).value = defaultPos[0];
        document.getElementById(`lng-${currentIndex}`).value = defaultPos[1];

        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById(`lat-${currentIndex}`).value = pos.lat;
            document.getElementById(`lng-${currentIndex}`).value = pos.lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById(`lat-${currentIndex}`).value = e.latlng.lat;
            document.getElementById(`lng-${currentIndex}`).value = e.latlng.lng;
        });

        // Simpan ke global
        window[`map_${currentIndex}`] = map;
        window[`marker_${currentIndex}`] = marker;

        // Paksa map render
        setTimeout(() => map.invalidateSize(), 0);
    }, 100); // cukup 100ms

    locationIndex++;
}


        function resetLocation(index) {
            const defaultPos = [-6.2088, 106.8456];
            window[`marker_${index}`].setLatLng(defaultPos);
            window[`map_${index}`].setView(defaultPos, 13);
            document.getElementById(`lat-${index}`).value = defaultPos[0];
            document.getElementById(`lng-${index}`).value = defaultPos[1];
        }

        function removeWorkLocation(index) {
            const box = document.getElementById(`location-box-${index}`);
            if (box) box.remove();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/admin/clients/edit.blade.php ENDPATH**/ ?>