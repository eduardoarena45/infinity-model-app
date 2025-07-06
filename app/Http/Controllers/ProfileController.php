<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Pega todos os serviços disponíveis para os checkboxes
        $servicosDisponiveis = Servico::orderBy('nome')->get();

        return view('profile.edit-acompanhante', [
            'user' => $request->user(),
            'perfil' => $perfil,
            'servicosDisponiveis' => $servicosDisponiveis,
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

        // Lida com o upload da foto principal
        if ($request->hasFile('imagem_principal_url')) {
            // Apaga a foto antiga, se existir
            if ($perfil->imagem_principal_url) {
                Storage::disk('public')->delete($perfil->imagem_principal_url);
            }
            // Guarda a nova foto
            $path = $request->file('imagem_principal_url')->store('perfis', 'public');

            // Aplica a marca d'água
            $manager = new ImageManager(new Driver());
            $imagePath = Storage::disk('public')->path($path);
            $image = $manager->read($imagePath);
            $logoPath = public_path('images/logo-watermark.png');
            if (file_exists($logoPath)) {
                $image->place($logoPath, 'bottom-right', 10, 10, 70);
            }
            $image->save($imagePath);

            $validatedData['imagem_principal_url'] = $path;
            // Se a foto principal for alterada, o perfil volta a ficar pendente
            $validatedData['status'] = 'pendente';
        }

        // Atualiza os dados do perfil
        $perfil->update($validatedData);

        // Sincroniza os serviços selecionados
        $perfil->servicos()->sync($request->input('servicos', []));

        return redirect()->route('profile.edit')->with('status', 'perfil-publico-atualizado');
    }

    /**
     * Lida com o upload de novas mídias para a galeria.
     */
    public function uploadGaleria(Request $request)
    {
        $request->validate(['midias.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,wmv,avi|max:10240']);
        $perfil = $request->user()->acompanhante;

        if ($request->hasFile('midias')) {
            foreach ($request->file('midias') as $ficheiro) {
                $path = $ficheiro->store('galerias', 'public');
                $tipo = Str::startsWith($ficheiro->getMimeType(), 'image') ? 'foto' : 'video';
                
                // Guarda a nova mídia com o status 'pendente'
                $perfil->midias()->create([
                    'caminho_arquivo' => $path,
                    'tipo' => $tipo,
                    'status' => 'pendente',
                ]);
            }
            // Também definimos o perfil principal como pendente para forçar uma revisão
            $perfil->update(['status' => 'pendente']);
        }
        return redirect()->route('profile.edit')->with('status', 'galeria-atualizada');
    }

    /**
     * Apaga uma mídia específica da galeria.
     */
    public function destroyMidia(Midia $midia)
    {
        // Garante que a utilizadora só pode apagar as próprias fotos
        if ($midia->acompanhante->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete($midia->caminho_arquivo);
        $midia->delete();
        return redirect()->route('profile.edit')->with('status', 'foto-removida');
    }
}
