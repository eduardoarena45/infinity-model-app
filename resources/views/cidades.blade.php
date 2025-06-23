@extends('layouts.public')

@section('title', 'Bem-vindo(a) - Encontre a melhor companhia')

@section('content')
<div class="relative bg-gray-800 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Encontre a companhia</span>
                        <span class="block text-pink-500 xl:inline">perfeita para si.</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Explore perfis verificados nas principais cidades. A sua melhor experiência começa aqui.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="w-full">
                            <label for="search-city" class="sr-only">Buscar cidade</label>
                            <input type="text" id="search-city" placeholder="Digite o nome da sua cidade..." class="w-full px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551963831-b3b1ca40c98e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Imagem de uma cidade à noite">
    </div>
</div>

<div class="py-12 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-extrabold text-center text-gray-900 dark:text-white">Nossas Vitrines</h2>
        <div id="city-list" class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($cidades as $cidade)
                <a href="{{ route('vitrine.por.cidade', ['cidade' => $cidade->cidade]) }}" class="city-item block text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-md hover:bg-pink-100 dark:hover:bg-pink-900 transition-colors">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $cidade->cidade }}</h3>
                </a>
            @empty
                <p class="col-span-full text-center text-gray-500">Nenhuma cidade com perfis disponíveis no momento.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.getElementById('search-city').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cityItems = document.querySelectorAll('.city-item');
        cityItems.forEach(item => {
            let cityName = item.querySelector('h3').textContent.toLowerCase();
            if (cityName.includes(filter)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection