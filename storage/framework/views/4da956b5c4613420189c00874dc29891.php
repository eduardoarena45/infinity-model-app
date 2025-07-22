<?php $__env->startSection('title', "Perfil de {$acompanhante->nome_artistico}"); ?>

<?php $__env->startSection('content'); ?>

<div class="bg-gray-100 dark:bg-gray-900 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">

            <div class="relative">
                <div class="h-48 bg-gray-200 dark:bg-gray-700 bg-cover bg-center" style="background-image: url('<?php echo e($acompanhante->foto_principal_url); ?>');">
                    <div class="h-full w-full bg-black bg-opacity-50 backdrop-blur-md"></div>
                </div>
                <div class="absolute top-24 left-1/2 -translate-x-1/2 md:left-12 md:-translate-x-0">
                    <a href="<?php echo e($acompanhante->foto_principal_url); ?>" data-fancybox="gallery" data-caption="<?php echo e($acompanhante->nome_artistico); ?>">
                        <img src="<?php echo e($acompanhante->foto_principal_url); ?>" alt="Foto de <?php echo e($acompanhante->nome_artistico); ?>" class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                    </a>
                </div>
            </div>

            <div class="pt-20 md:pt-8 pb-8 px-4 sm:px-8">
                <div class="text-center md:text-left md:ml-48">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center justify-center md:justify-start gap-x-2">
                        <span><?php echo e($acompanhante->nome_artistico); ?></span>
                        <?php if($acompanhante->is_verified): ?>
                            <span title="Perfil Verificado"><svg class="w-7 h-7 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a.75.75 0 00-1.06-1.06L9 10.94l-1.72-1.72a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.06 0l3.75-3.75z" clip-rule="evenodd" /></svg></span>
                        <?php endif; ?>
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mt-1"><?php echo e($acompanhante->cidade->nome ?? 'Cidade não informada'); ?>, <?php echo e($acompanhante->idade); ?> anos</p>
                </div>
                <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center">
                        <span class="text-gray-500 dark:text-gray-400">Valor</span>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">R$ <?php echo e(number_format($acompanhante->valor_hora, 2, ',', '.')); ?> / hora</p>
                    </div>
                    <a href="https://wa.me/55<?php echo e(preg_replace('/\D/', '', $acompanhante->whatsapp)); ?>" target="_blank" class="w-full md:w-auto flex items-center justify-center bg-green-500 text-white font-bold py-3 px-8 rounded-lg text-lg hover:bg-green-600 transition-colors shadow-lg">
                        <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                        <span>Contato por WhatsApp</span>
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-8 space-y-12">
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Sobre mim</h3>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap"><?php echo e($acompanhante->descricao); ?></p>
                </section>

                <?php if($acompanhante->servicos->isNotEmpty()): ?>
                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Serviços</h3>
                    <div class="flex flex-wrap gap-3">
                        <?php $__currentLoopData = $acompanhante->servicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $servico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-block bg-pink-100 text-pink-800 text-sm font-medium px-3 py-1 rounded-full dark:bg-pink-900 dark:text-pink-300"><?php echo e($servico->nome); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
                <?php endif; ?>

                <section>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Galeria</h3>
                    <?php if($acompanhante->midias->where('status', 'aprovado')->isNotEmpty()): ?>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
                            <?php $__currentLoopData = $acompanhante->midias->where('status', 'aprovado'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $midia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                
                                <?php if($midia->type === 'image'): ?>
                                    <a href="<?php echo e(Storage::url($midia->path)); ?>" data-fancybox="gallery" data-caption="<?php echo e($acompanhante->nome_artistico); ?>">
                                        <img src="<?php echo e(Storage::url($midia->path)); ?>" class="rounded-lg object-cover w-full h-48 hover:opacity-80 transition-opacity shadow-md" alt="Foto da galeria">
                                    </a>
                                
                                
                                <?php elseif($midia->type === 'video' && $midia->thumbnail_path): ?>
                                    <a href="<?php echo e(Storage::url($midia->path)); ?>" data-fancybox="gallery" data-caption="<?php echo e($acompanhante->nome_artistico); ?>">
                                        <div class="relative w-full h-48 bg-black rounded-lg shadow-md group">
                                            <img src="<?php echo e(Storage::url($midia->thumbnail_path)); ?>" class="w-full h-full object-cover rounded-lg group-hover:opacity-80 transition-opacity" alt="Capa do vídeo">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="bg-black bg-opacity-50 rounded-full p-3">
                                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center">Nenhuma mídia na galeria ainda.</p>
                    <?php endif; ?>
                </section>
                
            </div>
        </div>
        <div class="text-center mt-8">
            <a href="<?php echo e(url()->previous()); ?>" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">&larr; Voltar</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\infinity_model_app\resources\views/perfil.blade.php ENDPATH**/ ?>