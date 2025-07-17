<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            :root { 
                --color-primary: #4E2A51; 
                --color-accent: #B76E79; 
                --color-neutral: #36454F; 
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
            <div class="absolute inset-0 z-0">
                <img src="<?php echo e(asset('images/meu-banner.jpg')); ?>" class="w-full h-full object-cover opacity-30 blur-sm">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            </div>

            
            <div class="relative z-10">
                <div>
                    <a href="/">
                        <span class="text-4xl font-extrabold text-white">
                            Infinity Model
                        </span>
                    </a>
                </div>

                
                <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 bg-opacity-70 backdrop-blur-lg shadow-2xl overflow-hidden sm:rounded-lg">
                    <?php echo e($slot); ?>

                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\laragon\www\infinity_model_app\resources\views/layouts/guest.blade.php ENDPATH**/ ?>