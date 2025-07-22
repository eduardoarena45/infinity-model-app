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
            Painel de Controle
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <?php switch($acompanhante->status ?? 'pendente'):
                
                case ('aprovado'): ?>
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg dark:bg-green-900/40 dark:border-green-500 dark:text-green-300" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold dark:text-green-200">Perfil Aprovado!</p>
                                <p class="text-sm">Parabéns! Seu perfil está ativo e visível na vitrine do site para todos os visitantes.</p>
                            </div>
                        </div>
                    </div>
                    <?php break; ?>

                <?php case ('pendente'): ?>
                    <div class="mb-6 bg-amber-100 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-lg dark:bg-amber-900/40 dark:border-amber-500 dark:text-amber-300" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="h-6 w-6 text-amber-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold dark:text-amber-200">Perfil em Análise</p>
                                <p class="text-sm">Seu perfil foi recebido e está aguardando a revisão da nossa equipe. Este processo geralmente leva até 24 horas.</p>
                            </div>
                        </div>
                    </div>
                    <?php break; ?>

                <?php case ('rejeitado'): ?>
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg dark:bg-red-900/40 dark:border-red-500 dark:text-red-300" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold dark:text-red-200">Perfil Rejeitado</p>
                                <p class="text-sm">Atenção: Seu perfil precisa de ajustes. Por favor, vá em "Editar Perfil" e corrija as informações conforme as diretrizes do site.</p>
                            </div>
                        </div>
                    </div>
                    <?php break; ?>

            <?php endswitch; ?>
            

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    <h3 class="text-2xl font-bold mb-2">Olá, <?php echo e(Auth::user()->name); ?>!</h3>
                    <p class="text-md text-gray-600 dark:text-gray-400 mb-6">Veja como seu perfil está aparecendo na vitrine para os clientes.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        
                        <div class="md:col-span-1">
                            <?php echo $__env->make('partials.acompanhante-card', ['perfil' => $acompanhante, 'isDestaque' => $acompanhante->is_featured], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <div class="md:col-span-2 flex flex-col space-y-4">
                            <p class="text-center text-gray-500 dark:text-gray-400">O que você gostaria de fazer agora?</p>
                            
                            <a href="<?php echo e(route('profile.edit')); ?>" class="w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-transform hover:scale-105">
                                Editar Perfil Completo
                            </a>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <a href="<?php echo e(route('galeria.gerir')); ?>" class="w-full text-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Gerir Galeria de Fotos
                                </a>
                                <a href="<?php echo e(route('planos.selecionar')); ?>" class="w-full text-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Ver Meu Plano
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
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
<?php /**PATH C:\laragon\www\infinity_model_app\resources\views/dashboard.blade.php ENDPATH**/ ?>