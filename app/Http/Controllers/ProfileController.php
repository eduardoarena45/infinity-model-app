<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importa a classe Storage

class ProfileController extends Controller
{
    // Mostra o formulário de edição do perfil da acompanhante
    public function edit(Request $request)
    {
        // Pega o perfil da acompanhante associado ao usuário logado
        // Se não encontrar, cria um novo perfil vazio para ele.
        $perfil = $request->user()->acompanhante()->firstOrCreate([]);

        return view('profile.edit-acompanhante', [
            'user' => $request->user(),
            'perfil' => $perfil,
        ]);
    }

    // Salva as alterações do formulário
    public function update(Request $request)
    {
        $user = $request->user();
        $perfil = $user->acompanhante;

        // Validação dos dados que chegam do formulário
        $validatedData = $request->validate([
            'nome_artistico' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'cidade' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'descricao_curta' => ['required', 'string'],
            'valor_hora' => ['required', 'numeric', 'min:0'],
            // Validação para a nova imagem: deve ser uma imagem, com no máximo 2MB.
            'imagem_principal_url' => ['nullable', 'image', 'max:2048'],
        ]);

        // LÓGICA DE UPLOAD DA IMAGEM
        if ($request->hasFile('imagem_principal_url')) {
            // 1. Apaga a imagem antiga para não ocupar espaço
            if ($perfil->imagem_principal_url) {
                Storage::disk('public')->delete($perfil->imagem_principal_url);
            }

            // 2. Salva a nova imagem e pega o caminho
            $path = $request->file('imagem_principal_url')->store('perfis', 'public');

            // 3. Adiciona o novo caminho aos dados validados
            $validatedData['imagem_principal_url'] = $path;
        }

        // Adiciona o estado fixo por enquanto
        $validatedData['estado'] = 'SP';

        // Atualiza o perfil com todos os dados (incluindo o caminho da nova imagem, se houver)
        $perfil->update($validatedData);

        // Redireciona de volta para a mesma página com uma mensagem de sucesso
        return redirect()->route('profile.edit')->with('status', 'perfil-publico-atualizado');
    }
}