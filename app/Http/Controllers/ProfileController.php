<?php

namespace App\Http\Controllers; // <-- ERRO DE SINTAXE CORRIGIDO AQUI

use App\Models\Estado;
use App\Models\Media;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $acompanhante = $user->acompanhante()->firstOrCreate(['user_id' => $user->id]);
        $estados = Estado::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        $servicosAtuais = $acompanhante->servicos->pluck('id')->toArray();
        $cidades = [];
        if ($acompanhante->cidade_id && $acompanhante->cidade) {
            $cidades = $acompanhante->cidade->estado->cidades()->orderBy('nome')->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'acompanhante' => $acompanhante,
            'estados' => $estados,
            'cidades' => $cidades,
            'servicos' => $servicos,
            'servicosAtuais' => $servicosAtuais,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $acompanhante = $user->acompanhante;

        $validatedData = $request->validate([
            'nome_artistico' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'cidade_id' => ['required', 'exists:cidades,id'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'descricao' => ['required', 'string'],
            'valor_hora' => ['required', 'numeric'],
            'servicos' => ['nullable', 'array'],
            'servicos.*' => ['exists:servicos,id'],
            'foto_principal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('foto_principal')) {
            if ($acompanhante->foto_principal_path) {
                Storage::disk('public')->delete($acompanhante->foto_principal_path);
            }
            $path = $request->file('foto_principal')->store('perfis', 'public');
            $acompanhante->foto_principal_path = $path;
        }

        $acompanhante->fill($request->except(['foto_principal', 'servicos']));
        $acompanhante->status = 'pendente';
        $acompanhante->save();

        $acompanhante->servicos()->sync($request->input('servicos', []));

        return back()->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);
        $user = $request->user();
        if ($user->private_avatar_path) {
            Storage::disk('public')->delete($user->private_avatar_path);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['private_avatar_path' => $path]);
        return back()->with('status', 'avatar-updated');
    }

    public function gerirGaleria(): View
    {
        $user = Auth::user();
        $media = $user->media()->orderBy('created_at', 'desc')->get();
        $photo_count = $media->count();
        
        // Agora busca o limite dinâmico do usuário
        $photo_limit = $user->getPhotoLimit();

        return view('profile.gerir-galeria', [
            'media' => $media,
            'photo_count' => $photo_count,
            'photo_limit' => $photo_limit,
        ]);
    }
    
    public function uploadGaleria(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // Agora busca o limite dinâmico do usuário
        $limit = $user->getPhotoLimit();
        
        $current_count = $user->media()->count();
        if (($current_count + count($request->file('fotos'))) > $limit) {
            return back()->with('error_message', "Limite de {$limit} fotos atingido!");
        }
        $request->validate(['fotos' => 'required', 'fotos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120']);
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $path = $file->store('galerias/' . $user->id, 'public');
                Media::create(['user_id' => $user->id, 'type' => 'image', 'path' => $path, 'status' => 'pendente']);
            }
        }
        return back()->with('status', 'gallery-updated')->with('success_message', 'Fotos enviadas com sucesso!');
    }

    public function destroyMidia(Media $media): RedirectResponse
    {
        if ($media->user_id !== auth()->id()) { abort(403); }
        Storage::disk('public')->delete($media->path);
        $media->delete();
        return back()->with('status', 'gallery-updated')->with('success_message', 'Foto removida!');
    }

    // A função getPhotoLimit() foi REMOVIDA daqui, pois agora está no Model User.
}
