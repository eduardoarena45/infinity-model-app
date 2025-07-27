<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Painel de Controle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Início do Bloco de Estatísticas --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Suas Estatísticas</h2>
                
                {{-- Cards de Estatísticas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Visualizações Hoje</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $viewsToday }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Visualizações este Mês</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $viewsThisMonth }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total de Visualizações</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalViews }}</p>
                    </div>
                </div>

                {{-- Gráfico de Visualizações --}}
                <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Visualizações nos Últimos 7 Dias</h3>
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>
            {{-- Fim do Bloco de Estatísticas --}}

            {{-- STATUS BOX --}}
            @switch($acompanhante->status ?? 'pendente')
                
                @case('aprovado')
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
                    @break

                @case('pendente')
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
                    @break

                @case('rejeitado')
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
                    @break

            @endswitch

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    
                    {{-- LINHA ALTERADA PARA USAR O NOME ARTÍSTICO --}}
                    <h3 class="text-2xl font-bold">Olá, {{ $acompanhante->nome_artistico ?? Auth::user()->name }}!</h3>
                    
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Bem-vinda ao seu painel de controle. Use o menu à esquerda para gerenciar seu perfil, galeria e plano.</p>
                    
                    {{-- O BLOCO DE BOTÕES FOI REMOVIDO DESTA ÁREA --}}

                </div>
            </div>
        </div>
    </div>

    {{-- Adicione este script no final do ficheiro --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('viewsChart').getContext('2d');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'bar', // ou 'line' para um gráfico de linha
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Visualizações por Dia',
                        data: chartData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Garante que só mostra números inteiros no eixo Y
                                precision: 0 
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Esconde a legenda para um visual mais limpo
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
