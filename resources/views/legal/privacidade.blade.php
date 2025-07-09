@extends('layouts.public')
@section('title', 'Política de Privacidade')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="prose dark:prose-invert">
            <h1>Política de Privacidade</h1>
            <p>Última atualização: [INSERIR DATA]</p>

            <h2>1. Informações que Coletamos</h2>
            <p>Coletamos as informações que você nos fornece diretamente, tais como:</p>
            <ul>
                <li>Informações de registo: e-mail e senha.</li>
                <li>Informações do perfil: nome artístico, fotos, vídeos, descrição, cidade, estado, etc.</li>
            </ul>

            <h2>2. Como Usamos as Suas Informações</h2>
            <p>Usamos as informações que coletamos para operar, manter e fornecer as funcionalidades da nossa Plataforma, incluindo a exibição do seu perfil para os visitantes.</p>

            {{-- ADICIONE AQUI OUTRAS CLÁUSULAS IMPORTANTES --}}

            <h2>3. Partilha de Informações</h2>
            <p>Não partilhamos as suas informações pessoais, como o seu e-mail, com terceiros, exceto conforme necessário para operar a plataforma ou se exigido por lei.</p>
        </div>
    </div>
@endsection