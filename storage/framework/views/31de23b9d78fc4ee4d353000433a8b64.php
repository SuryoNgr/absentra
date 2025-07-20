<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - ERA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="icon" href="<?php echo e(asset('images/logo-era.png')); ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
        }

        .sidebar {
            width: 240px;
            background: #1e3a8a;
            height: 100vh;
            padding: 20px;
            position: fixed;
            color: white;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background: #2563eb;
        }

        .content {
            margin-left: 240px;
            width: calc(100% - 240px);
        }


        .navbar {
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
             box-shadow: 3px 3px 25px 2px rgba(0,0,0,0.56);
            -webkit-box-shadow: 3px 3px 25px 2px rgba(0,0,0,0.56);
            -moz-box-shadow: 3px 3px 25px 2px rgba(0,0,0,0.56);
        }

        .navbar .user-info {
            font-size: 14px;
            color: #555;
        }

        .navbar form {
            margin: 0;
        }

        .navbar button {
            background: #ef4444;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .navbar button:hover {
            background: #dc2626;
        }

        main {
            padding: 20px;
            max-width: 100%;
            overflow-x: auto;
        }
        
    </style>
</head>
<body>
     

<div class="sidebar">
    <img src="<?php echo e(asset('images/logo-era.png')); ?>" alt="Logo ERA" style="width: 120px; height: auto;">
    <a href="<?php echo e(route('supervisor.petugas.index')); ?>"><i class="fas fa-users me-2"></i> Daftar Petugas</a>
    <a href="<?php echo e(route('supervisor.riwayat-absensi.index')); ?>"><i class="fas fa-clipboard-check me-2"></i> Riwayat Absensi</a>
    <a href="<?php echo e(route('supervisor.riwayat-laporan.index')); ?>"><i class="fas fa-clipboard-list me-2"></i> Riwayat Laporan</a>
</div>


    <div class="content">
        <div class="navbar">
            <div class="user-info">
                <?php echo e(auth('web')->user()->name ?? 'Admin'); ?>

            </div>

            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit">Logout</button>
            </form>
        </div>

        <main>
            <?php echo $__env->yieldContent('content'); ?>
             <?php echo $__env->yieldContent('scripts'); ?>
        </main>
    </div>

</body>
</html>
<?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/layouts/supervisor.blade.php ENDPATH**/ ?>