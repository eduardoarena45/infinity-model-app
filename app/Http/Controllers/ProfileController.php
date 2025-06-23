<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $perfil = $request->user()->acompanhante()->firstOrCreate([]);
        $perfil->load('midias');
        return view('profile.edit-acompanhante', ['user' => $request->user(),'perfil' => $perfil]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $perfil = $user->acompanhante;
        $validatedData = $request->validate(['nome_artistico' => ['required','string','max:255'],'data_nascimento' => ['required','date'],'cidade' => ['required','string','max:255'],'whatsapp' => ['required','string','max:20'],'descricao_curta' => ['required','string'],'valor_hora' => ['required','numeric','min:0'],'imagem_principal_url' => ['nullable','image','max:2048'],]);
        if ($request->hasFile('imagem_principal_url')) {
            if ($perfil->imagem_principal_url) { Storage::disk('public')->delete($perfil->imagem_principal_url); }
            $path = $request->file('imagem_principal_url')->store('perfis', 'public');
            $validatedData['imagem_principal_url'] = $path;
        }
        $validatedData['estado'] = 'SP';
        $perfil->update($validatedData);
        return redirect()->route('profile.edit')->with('status', 'perfil-publico-atualizado');
    }

    public function uploadGaleria(Request $request)
    {
        $request->validate(['midias.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,wmv,avi|max:10240']);
        $perfil = $request->user()->acompanhante;
        if ($request->hasFile('midias')) {
            foreach ($request->file('midias') as $ficheiro) {
                $path = $ficheiro->store('galerias', 'public');
                $tipo = Str::startsWith($ficheiro->getMimeType(), 'image') ? 'foto' : 'video';
                $perfil->midias()->create(['caminho_arquivo' => $path, 'tipo' => $tipo]);
            }
        }
        return redirect()->route('profile.edit')->with('status', 'galeria-atualizada');
    }

    public function destroyMidia(Midia $midia)
    {
        if ($midia->acompanhante->user_id !== Auth::id()) { abort(403); }
        Storage::disk('public')->delete($midia->caminho_arquivo);
        $midia->delete();
        return redirect()->route('profile.edit')->with('status', 'foto-removida');
    }
}