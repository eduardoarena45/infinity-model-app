@extends('layouts.onboarding')

@section('title', 'Instruções de Pagamento')

@section('content')
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 mb-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 text-center">

                <h1 class="text-3xl font-bold text-[--color-primary] dark:text-cyan-400">Quase lá!</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Para ativar a sua assinatura, por favor, siga os passos abaixo.</p>

                <div class="mt-6 border-t border-b dark:border-gray-700 py-4">
                    <p class="text-lg">Você escolheu: <span class="font-bold">{{ $assinatura->plano->nome }}</span></p>
                    <p class="text-3xl font-extrabold my-2">Valor: R$ {{ number_format($assinatura->plano->preco, 2, ',', '.') }}</p>
                </div>

                <div class="mt-6">
                    <h2 class="font-bold text-lg mb-4">Pague com PIX</h2>

                    <div class="flex justify-center">
                        {{-- IMPORTANTE: Substitua 'images/meu-qrcode-pix.png' pelo caminho da sua imagem de QR Code --}}
                        <img src="{{ asset('images/meu-qrcode-pix.png') }}" alt="QR Code PIX" class="w-48 h-48 border dark:border-gray-600 p-2 rounded-lg">
                    </div>

                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Ou use a chave PIX (Copia e Cola):</p>
                        <div x-data="{ chave: 'd8649b39-2e55-4272-9139-b7123cb6877d' }" class="mt-2 flex justify-center">
                            <input type="text" :value="chave" readonly class="w-auto text-center bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-l-md">
                            <button @click="navigator.clipboard.writeText(chave); alert('Chave PIX copiada!')" class="bg-gray-600 text-white px-4 rounded-r-md hover:bg-gray-700">Copiar</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 rounded-lg text-left">
                    <p class="font-bold">Instruções Finais:</p>
                    <ol class="list-decimal list-inside mt-2 text-sm space-y-1">
                        <li>Realize o pagamento do valor exato.</li>
                        <li>Após o pagamento, clique no botão abaixo para <strong>enviar o comprovativo</strong> para o nosso WhatsApp de suporte.</li>
                        <li>A sua assinatura será ativada manualmente em poucas horas.</li>
                    </ol>
                </div>

                <div class="mt-6">
                    @php
                        // IMPORTANTE: Substitua '5516999999999' pelo seu número de WhatsApp com o código do país
                        $numeroWhatsapp = '5516994067222';
                        $mensagem = "Olá! Segue o comprovativo de pagamento para a assinatura do plano " . $assinatura->plano->nome . ". Meu nome de utilizadora é: " . Auth::user()->name;
                        $linkWhatsapp = "https://wa.me/" . $numeroWhatsapp . "?text=" . urlencode($mensagem);
                    @endphp
                    <a href="{{ $linkWhatsapp }}" target="_blank" class="w-full flex items-center justify-center bg-green-500 text-white font-bold py-3 px-8 rounded-lg text-lg hover:bg-green-600 transition-colors shadow-lg">
                        <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                        <span>Enviar Comprovativo via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Incluímos o AlpineJS aqui caso ainda não esteja no layout --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
