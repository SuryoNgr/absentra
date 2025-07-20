<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indonesia ERA - Login</title>
    <link rel="icon" href="<?php echo e(asset('images/logo-era.png')); ?>" type="image/png">
    <style>
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 100px;
            height: auto;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-img {
            max-width: 200px;
            object-fit: contain;
            border-radius: 12px;
           
        }


        .building {
            width: 6px;
            background: #1e3a8a;
            border-radius: 1px;
        }

        .building:nth-child(1) { height: 15px; }
        .building:nth-child(2) { height: 12px; }
        .building:nth-child(3) { height: 18px; }
        .building:nth-child(4) { height: 10px; }
        .building:nth-child(5) { height: 14px; }

        h1 {
            color: white;
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .login-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
        .alert {
            background-color: rgba(255, 0, 0, 0.1);
            color: red;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .form-error {
            color: #ffe4e4;
            font-size: 13px;
            margin-top: 5px;
            padding-left: 3px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="<?php echo e(asset('images/logo-era.png')); ?>" alt="Logo ERA" class="logo-img">   
        </div>
        
        <h1><b>Login</b></h1>

        
        <?php if(session('msg')): ?>
            <div class="alert">
                <?php echo e(session('msg')); ?>

            </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
            <div class="alert">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo e(route('auth.verify')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="<?php echo e(old('email')); ?>"
                    required
                >
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                >
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="form-error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
<?php /**PATH D:\New Folder\Project Newest\NewEra\resources\views/auth/login.blade.php ENDPATH**/ ?>