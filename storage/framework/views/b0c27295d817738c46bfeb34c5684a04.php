<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escolha o seu Plano
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-lg text-gray-600 dark:text-gray-400">Selecione o plano que melhor se adapta às suas necessidades para começar.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                
                <?php $__currentLoopData = $planos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plano): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col <?php if($plano->destaque): ?> border-2 border-yellow-500 <?php else: ?> border-2 border-gray-200 dark:border-gray-700 <?php endif; ?>">
                        <h2 class="text-2xl font-bold text-center <?php if($plano->preco == 0): ?> text-gray-500 dark:text-gray-400 <?php else: ?> text-[--color-primary] dark:text-cyan-400 <?php endif; ?>"><?php echo e($plano->nome); ?></h2>
                        <p class="text-4xl font-extrabold text-center my-4 dark:text-white">R$ <?php echo e(number_format($plano->preco, 2, ',', '.')); ?></p>
                        
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400 flex-grow">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Perfil Verificado
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                WhatsApp Visível
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <?php echo e($plano->limite_fotos); ?> Fotos na Galeria
                            </li>

                            <?php if($plano->permite_videos): ?>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Permite Vídeos na Galeria
                                </li>
                            <?php else: ?>
                                <li class="flex items-center text-gray-400 line-through">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Vídeos na Galeria
                                </li>
                            <?php endif; ?>

                            <?php if($plano->destaque): ?>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Perfil em Destaque
                                </li>
                            <?php else: ?>
                                <li class="flex items-center text-gray-400 line-through">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Perfil em Destaque
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                        <div class="mt-6">
                            <?php if($plano->preco == 0): ?>
                                <span class="w-full block text-center bg-gray-200 dark:bg-gray-700 text-gray-500 font-bold py-3 px-6 rounded-lg">Plano Atual</span>
                            <?php else: ?>
                                <form action="<?php echo e(route('planos.assinar', $plano)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full bg-[--color-primary] text-white font-bold py-3 px-6 rounded-lg hover:opacity-90 transition-opacity">Assinar Plano</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\infinity_model_app\resources\views/planos/selecionar.blade.php ENDPATH**/ ?>