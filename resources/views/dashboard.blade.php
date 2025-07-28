<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Painel de Controle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MENSAGEM DE BOAS-VINDAS (COM O PRIMEIRO NOME) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    {{-- LINHA ALTERADA PARA MOSTRAR O PRIMEIRO NOME --}}
                    <h3 class="text-2xl font-bold">Olá, {{ $acompanhante->nome_artistico ?? ucfirst(explode('@', Auth::user()->email)[0]) }}!</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Bem-vinda ao seu painel! As estatísticas abaixo são atualizadas em tempo real. Cada vez que um visitante acessa o seu perfil público na vitrine, os números de visualização aumentam, mostrando o alcance e o interesse no seu perfil.
                    </p>
                </div>
            </div>

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

        </div>
    </div>

    {{-- Script para o gráfico --}}
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
