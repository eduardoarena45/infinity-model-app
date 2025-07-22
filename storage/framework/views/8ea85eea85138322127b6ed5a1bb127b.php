<?php $__env->startSection('title', 'Bem-vindo(a) - Encontre a melhor companhia'); ?>

<?php $__env->startSection('content'); ?>

<div class="relative bg-black">
    <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
        <img src="<?php echo e(asset('images/meu-banner.jpg')); ?>" alt="Banner principal do Infinity Model" class="w-full h-full object-center object-cover">
    </div>
    <div aria-hidden="true" class="absolute inset-0 bg-black opacity-75"></div>
    <div class="relative max-w-4xl mx-auto py-32 px-6 flex flex-col items-center text-center sm:py-48 lg:px-0">
        <h1 class="text-4xl font-extrabold tracking-tight text-white lg:text-6xl" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">
            Bem-vindo ao maior site de acompanhantes do Brasil
        </h1>
        <p class="mt-4 text-xl text-gray-200" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">
            Explore perfis verificados e encontre a companhia ideal para momentos únicos.
        </p>
        <button id="open-cities-modal-btn" class="mt-8 inline-block bg-white border border-transparent rounded-md py-3 px-8 text-base font-medium text-black hover:bg-gray-200 transition-colors">
            Ver Cidades
        </button>
    </div>
</div>
<div id="cities-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 max-w-lg w-full m-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">Selecione a Região</h2>
            <button id="close-cities-modal-btn" class="text-gray-500 hover:text-gray-800 dark:hover:text-white text-3xl leading-none">&times;</button>
        </div>

        
        <div class="space-y-4">
            
            <div>
                <label for="estado-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">1. Escolha um Estado</label>
                <select id="estado-select" class="mt-1 block w-full p-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    
                    <?php $__currentLoopData = $estados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($estado->id); ?>"><?php echo e($estado->nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label for="cidade-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">2. Escolha uma Cidade</label>
                <select id="cidade-select" class="mt-1 block w-full p-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                    <option value="">Aguardando seleção do estado...</option>
                </select>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('open-cities-modal-btn');
        const closeBtn = document.getElementById('close-cities-modal-btn');
        const modal = document.getElementById('cities-modal');

        const estadoSelect = document.getElementById('estado-select');
        const cidadeSelect = document.getElementById('cidade-select');

        // LÓGICA PARA CARREGAR CIDADES QUANDO UM ESTADO É SELECIONADO
        if (estadoSelect) {
            estadoSelect.addEventListener('change', function () {
                const estadoId = this.value;
                cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
                cidadeSelect.disabled = true;

                if (!estadoId) {
                    cidadeSelect.innerHTML = '<option value="">Aguardando seleção do estado...</option>';
                    return;
                }

                // Faz a chamada à API para buscar as cidades
                fetch(`/api/cidades/${estadoId}`)
                    .then(response => response.json())
                    .then(data => {
                        cidadeSelect.innerHTML = '<option value="">Selecione uma Cidade</option>';
                        data.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.nome; // Usamos o nome da cidade para o redirect
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                        cidadeSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao buscar cidades:', error);
                        cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                    });
            });
        }
        
        // LÓGICA PARA REDIRECIONAR QUANDO UMA CIDADE É SELECIONADA
        if (cidadeSelect) {
            cidadeSelect.addEventListener('change', function() {
                const cidadeNome = this.value;
                if (cidadeNome) {
                    // Monta a URL da vitrine e redireciona o usuário
                    window.location.href = `/vitrine/${cidadeNome}`;
                }
            });
        }

        // Código original para abrir e fechar o modal
        if (openBtn && modal) {
            openBtn.addEventListener('click', () => { modal.classList.remove('hidden'); });
        }
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', () => { modal.classList.add('hidden'); });
        }
        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal) { modal.classList.add('hidden'); }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\infinity_model_app\resources\views/cidades.blade.php ENDPATH**/ ?>