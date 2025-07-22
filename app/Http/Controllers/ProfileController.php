<?php

namespace App\Http\Controllers;

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

        $request->validate([
            'nome_artistico' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date'],
            'cidade_id' => ['required', 'exists:cidades,id'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'descricao' => ['required', 'string'],
            'valor_hora' => ['required', 'numeric'],
            'servicos' => ['nullable', 'array'],
            'servicos.*' => ['exists:servicos,id'],
            'foto_principal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_verificacao' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('foto_principal')) {
            if ($acompanhante->foto_principal_path) {
                Storage::disk('public')->delete($acompanhante->foto_principal_path);
            }
            $path = $request->file('foto_principal')->store('perfis', 'public');
            $acompanhante->foto_principal_path = $path;
        }

        if ($request->hasFile('foto_verificacao')) {
            if ($acompanhante->foto_verificacao_path) {
                Storage::disk('local')->delete($acompanhante->foto_verificacao_path);
            }
            $path = $request->file('foto_verificacao')->store('documentos_verificacao', 'local');
            $acompanhante->foto_verificacao_path = $path;
        }

        $acompanhante->fill($request->except(['foto_principal', 'foto_verificacao', 'servicos']));
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
        
        $photo_count = $media->where('type', 'image')->count();
        $photo_limit = $user->getPhotoLimit();

        $video_count = $media->where('type', 'video')->count();
        $video_limit = $user->getVideoLimit();

        return view('profile.gerir-galeria', [
            'media' => $media,
            'photo_count' => $photo_count,
            'photo_limit' => $photo_limit,
            'video_count' => $video_count,
            'video_limit' => $video_limit,
        ]);
    }
    
    public function uploadGaleria(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $limit = $user->getPhotoLimit();
        $current_count = $user->media()->where('type', 'image')->count();

        if (($current_count + count($request->file('fotos', []))) > $limit) {
            return back()->with('type', 'photo')->with('error_message', "Limite de {$limit} fotos atingido!");
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

        // Apaga a thumbnail se ela existir (para vídeos)
        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }
        // Apaga o arquivo principal (foto ou vídeo)
        Storage::disk('public')->delete($media->path);

        $media->delete();
        return back()->with('status', 'gallery-updated')->with('success_message', 'Mídia removida!');
    }

    // --- MÉTODO uploadVideo CORRIGIDO E COMPLETO ---
    public function uploadVideo(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $limit = $user->getVideoLimit();
        $current_count = $user->media()->where('type', 'video')->count();

        if (($current_count + count($request->file('videos', []))) > $limit) {
            return back()->with('type', 'video')->with('error_message', "Você atingiu o limite de {$limit} vídeos do seu plano!");
        }

        $request->validate([
            'videos' => 'required',
            'videos.*' => 'mimetypes:video/mp4,video/quicktime,video/mpeg|max:20480' // Limite de 20MB
        ]);

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                // 1. Salva o vídeo original
                $videoPath = $file->store('galerias/' . $user->id . '/videos', 'public');
                
                // 2. Prepara os caminhos para o comando ffmpeg
                $videoFullPath = storage_path('app/public/' . $videoPath);
                $thumbnailName = pathinfo($videoPath, PATHINFO_FILENAME) . '.jpg';
                $thumbnailRelativePath = 'galerias/' . $user->id . '/thumbnails/' . $thumbnailName;
                $thumbnailFullPath = storage_path('app/public/' . $thumbnailRelativePath);

                // Garante que o diretório de thumbnails exista
                Storage::disk('public')->makeDirectory('galerias/' . $user->id . '/thumbnails');

                // 3. Monta e executa o comando ffmpeg para criar a thumbnail
                $ffmpegCommand = "ffmpeg -i \"{$videoFullPath}\" -ss 00:00:01 -vframes 1 \"{$thumbnailFullPath}\"";
                shell_exec($ffmpegCommand);

                // 4. Cria o registro no banco de dados com os dois caminhos
                Media::create([
                    'user_id' => $user->id,
                    'type' => 'video',
                    'path' => $videoPath,
                    'thumbnail_path' => $thumbnailRelativePath, // Salva o caminho da thumbnail
                    'status' => 'pendente'
                ]);
            }
        }
        return back()->with('status', 'gallery-updated')->with('success_message', 'Vídeos enviados com sucesso!');
    }
}