<?php

namespace App\Http\Controllers;

use App\Models\Local;
use App\Models\Midia;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    /**
     * Mostra o formulário de edição do perfil da acompanhante.
     */
    public function edit(Request $request)
    {
        $perfil = $request->user()->acompanhante()->firstOrCreate([]);
        // Carrega as relações para usar na view
        $perfil->load('midias', 'servicos');
        // Pega todos os serviços disponíveis
        $servicosDisponiveis = Servico::orderBy('nome')->get();
        // Busca os estados distintos da nossa tabela de locais
        $estados = Local::select('estado')->distinct()->orderBy('estado')->get();

        return view('profile.edit-acompanhante', [
            'user' => $request->user(),
            'perfil' => $perfil,
            'servicosDisponiveis' => $servicosDisponiveis,
            'estados' => $estados, // Envia os nossos estados para a view
        ]);
    }

    /**
     * Atualiza as informações do perfil da acompanhante.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $perfil = $user->acompanhante;

        $validatedData = $request->validate([
            'nome_artistico' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'cidade' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:2'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'descricao_curta' => ['required', 'string'],
            'valor_hora' => ['required', 'numeric', 'min:0'],
            'imagem_principal_url' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('imagem_principal_url')) {
            if ($perfil->imagem_principal_url) {
                Storage::disk('public')->delete($perfil->imagem_principal_url);
            }
            $path = $request->file('imagem_principal_url')->store('perfis', 'public');
            $validatedData['imagem_principal_url'] = $path;
            $validatedData['status'] = 'pendente';
        }

        $perfil->update($validatedData);
        $perfil->servicos()->sync($request->input('servicos', []));

        return redirect()->route('profile.edit')->with('status', 'perfil-publico-atualizado');
    }

    /**
     * Mostra a página para gerir a galeria de mídia.
     */
    public function gerirGaleria(Request $request)
    {
        $perfil = $request->user()->acompanhante()->firstOrCreate([]);
        $perfil->load('midias');
        return view('profile.gerir-galeria', ['perfil' => $perfil]);
    }

    /**
     * Lida com o upload de novas mídias para a galeria.
     */
    public function uploadGaleria(Request $request)
    {
        $request->validate(['midias.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,wmv,avi|max:10240']);
        $perfil = $request->user()->acompanhante;
        $assinatura = $request->user()->assinatura;

        if (!$assinatura || !$assinatura->plano) {
            return back()->withErrors(['limite' => 'Você não tem um plano de assinatura ativo.']);
        }

        $plano = $assinatura->plano;
        $fotosCount = $perfil->midias()->where('tipo', 'foto')->count();
        $videosCount = $perfil->midias()->where('tipo', 'video')->count();

        if ($request->hasFile('midias')) {
            foreach ($request->file('midias') as $ficheiro) {
                $tipo = Str::startsWith($ficheiro->getMimeType(), 'image') ? 'foto' : 'video';

                if ($tipo === 'foto' && $fotosCount >= $plano->limite_fotos) {
                    return back()->withErrors(['limite' => "Limite de {$plano->limite_fotos} fotos atingido para o seu plano."]);
                }
                if ($tipo === 'video' && $videosCount >= $plano->limite_videos) {
                    return back()->withErrors(['limite' => "Limite de {$plano->limite_videos} vídeos atingido para o seu plano."]);
                }

                $path = $ficheiro->store('galerias', 'public');
                $perfil->midias()->create(['caminho_arquivo' => $path, 'tipo' => $tipo, 'status' => 'pendente']);

                if ($tipo === 'foto') $fotosCount++; else $videosCount++;
            }
            $perfil->update(['status' => 'pendente']);
        }
        return redirect()->route('galeria.gerir')->with('status', 'galeria-atualizada');
    }

    /**
     * Apaga uma mídia específica da galeria.
     */
    public function destroyMidia(Midia $midia)
    {
        if ($midia->acompanhante->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete($midia->caminho_arquivo);
        $midia->delete();
        return redirect()->route('galeria.gerir')->with('status', 'foto-removida');
    }
}
