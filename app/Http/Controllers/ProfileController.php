<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\Media;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
// INÍCIO DA CORREÇÃO FINAL: Importar a classe Rule para validação condicional
use Illuminate\Validation\Rule;
// FIM DA CORREÇÃO FINAL

class ProfileController extends Controller
{
    /**
     * Aplica uma imagem de marca d'água em uma imagem usando a biblioteca GD nativa do PHP.
     */
    private function aplicarMarcaDagua($caminhoImagemParaProcessar, $extensaoOriginal): string
    {
        // ... (código inalterado)
        $marcaDaguaPath = storage_path('app/watermark.png');

        if (!file_exists($marcaDaguaPath)) {
            return file_get_contents($caminhoImagemParaProcessar);
        }

        try {
            $marcaDagua = imagecreatefrompng($marcaDaguaPath);
            $extensao = strtolower($extensaoOriginal);

            $imagem = null;
            switch ($extensao) {
                case 'jpg':
                case 'jpeg':
                    $imagem = @imagecreatefromjpeg($caminhoImagemParaProcessar);
                    break;
                case 'png':
                    $imagem = @imagecreatefrompng($caminhoImagemParaProcessar);
                    break;
                case 'webp':
                    $imagem = @imagecreatefromwebp($caminhoImagemParaProcessar);
                    break;
                default:
                    return file_get_contents($caminhoImagemParaProcessar);
            }

            if ($imagem === false) {
                return file_get_contents($caminhoImagemParaProcessar);
            }

            $margem = 20;
            $marcaLargura = imagesx($marcaDagua);
            $marcaAltura = imagesy($marcaDagua);
            $posX = imagesx($imagem) - $marcaLargura - $margem;
            $posY = imagesy($imagem) - $marcaAltura - $margem;

            imagecopy($imagem, $marcaDagua, $posX, $posY, 0, 0, $marcaLargura, $marcaAltura);

            ob_start();
            imagejpeg($imagem, null, 90);
            $imagemFinal = ob_get_clean();

            imagedestroy($imagem);
            imagedestroy($marcaDagua);

            return $imagemFinal;

        } catch (\Exception $e) {
            \Log::error('Erro ao aplicar marca d\'água: ' . $e->getMessage());
            return file_get_contents($caminhoImagemParaProcessar);
        }
    }

    public function edit(Request $request): View
    {
        // ... (código inalterado)
        $user = $request->user();
        $acompanhante = $user->acompanhante()->firstOrCreate(['user_id' => $user->id]);
        $estados = Estado::orderBy('nome')->get();
        $servicos = Servico::orderBy('nome')->get();
        $servicosAtuais = $acompanhante->servicos->pluck('id')->toArray();
        $cidades = [];
        if ($acompanhante->cidade_id && $acompanhante->cidade) {
            $cidades = $acompanhante->cidade->estado->cidades()->orderBy('nome')->get();
        }

        $descricaoLimit = $user->getDescricaoLimit();

        return view('profile.edit', [
            'user' => $user,
            'acompanhante' => $acompanhante,
            'estados' => $estados,
            'cidades' => $cidades,
            'servicos' => $servicos,
            'servicosAtuais' => $servicosAtuais,
            'descricaoLimit' => $descricaoLimit,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $acompanhante = $user->acompanhante;
        $wasProfileIncomplete = empty($acompanhante->getOriginal('nome_artistico'));

        // ETAPA 1: SALVAR AS FOTOS PRIMEIRO, SE ELAS FOREM ENVIADAS.
        $requerModeracaoDeImagem = false;
        if ($request->hasFile('foto_principal')) {
            $request->validate(['foto_principal' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);

            if ($acompanhante->foto_principal_path) Storage::disk('public')->delete($acompanhante->foto_principal_path);
            $imagem = $request->file('foto_principal');
            $nomeArquivo = 'perfis/' . Str::random(40) . '.jpg';
            $imagemComMarca = $this->aplicarMarcaDagua($imagem->getRealPath(), $imagem->getClientOriginalExtension());
            Storage::disk('public')->put($nomeArquivo, $imagemComMarca);
            $acompanhante->foto_principal_path = $nomeArquivo;
            $requerModeracaoDeImagem = true;
        }

        if ($request->hasFile('foto_verificacao')) {
            $request->validate(['foto_verificacao' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']]);

            if ($acompanhante->foto_verificacao_path) Storage::disk('local')->delete($acompanhante->foto_verificacao_path);
            $path = $request->file('foto_verificacao')->store('documentos_verificacao', 'local');
            $acompanhante->foto_verificacao_path = $path;
            $requerModeracaoDeImagem = true;
        }

        if($requerModeracaoDeImagem) {
            $acompanhante->save();
            $acompanhante->refresh();
        }

        // ETAPA 2: VALIDAR TODOS OS DADOS DO FORMULÁRIO
        $descricaoLimit = $user->getDescricaoLimit();
        $descricaoRules = ['required', 'string'];
        if ($descricaoLimit) {
            $descricaoRules[] = 'max:' . $descricaoLimit;
        }

        // INÍCIO DA CORREÇÃO FINAL: Usar a validação condicional no campo correto.
        $request->validate([
            'nome_artistico' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'cidade_id' => ['required', 'exists:cidades,id'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'genero' => ['required', 'string', 'in:mulher,homem,trans'],
            'valor_hora' => ['required', 'numeric', 'min:0'],
            'descricao' => $descricaoRules,
            'foto_principal' => [Rule::requiredIf(!$acompanhante->foto_principal_path)],
            'foto_verificacao' => [Rule::requiredIf(!$acompanhante->foto_verificacao_path)],
            'valor_15_min' => ['nullable', 'numeric', 'min:0'],
            'valor_30_min' => ['nullable', 'numeric', 'min:0'],
            'valor_pernoite' => ['nullable', 'numeric', 'min:0'],
            'servicos' => ['nullable', 'array'],
            'servicos.*' => ['exists:servicos,id'],
        ], [
            'foto_principal.required' => 'O campo Foto Principal é obrigatório.',
            'foto_verificacao.required' => 'O campo Foto de Verificação com Documento é obrigatório.',
            'required' => 'O campo :attribute é obrigatório.',
            'date' => 'O campo :attribute deve ser uma data válida.',
            'before_or_equal' => 'Você deve ter pelo menos 18 anos.',
        ]);
        // FIM DA CORREÇÃO FINAL

        // ETAPA 3: ATUALIZAR OS DADOS DE TEXTO E O STATUS FINAL
        $dataToUpdate = $request->only([
            'nome_artistico', 'data_nascimento', 'cidade_id', 'whatsapp', 'genero',
            'valor_hora', 'descricao', 'valor_15_min', 'valor_30_min', 'valor_pernoite'
        ]);

        $jaFoiAprovado = in_array($acompanhante->getOriginal('status'), ['aprovado', 'rejeitado']);
        if (!($jaFoiAprovado && !$requerModeracaoDeImagem)) {
            $dataToUpdate['status'] = 'pendente';
        }

        $acompanhante->update($dataToUpdate);
        $acompanhante->servicos()->sync($request->input('servicos', []));
        Cache::flush();

        if ($wasProfileIncomplete && !empty($request->nome_artistico)) {
            return redirect()->route('galeria.gerir')->with('status', 'profile-updated-first-time');
        }

        return back()->with('status', 'profile-updated');
    }

    // ... (resto do código inalterado)
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate(['avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);
        $user = $request->user();

        if ($user->private_avatar_path) {
            Storage::disk('public')->delete($user->private_avatar_path);
        }

        $imagem = $request->file('avatar');
        $nomeArquivo = 'avatars/' . Str::random(40) . '.jpg';

        $imagemComMarca = $this->aplicarMarcaDagua($imagem->getRealPath(), $imagem->getClientOriginalExtension());
        Storage::disk('public')->put($nomeArquivo, $imagemComMarca);

        $user->update(['private_avatar_path' => $nomeArquivo]);
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

        $newMediaStatus = $user->acompanhante?->status === 'aprovado' ? 'aprovado' : 'pendente';

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $nomeArquivo = 'galerias/' . $user->id . '/' . Str::random(40) . '.jpg';
                $imagemComMarca = $this->aplicarMarcaDagua($file->getRealPath(), $file->getClientOriginalExtension());
                Storage::disk('public')->put($nomeArquivo, $imagemComMarca);
                Media::create([
                    'user_id' => $user->id,
                    'type' => 'image',
                    'path' => $nomeArquivo,
                    'status' => $newMediaStatus
                ]);
            }
            Cache::flush();
        }
        return back()->with('status', 'gallery-updated')->with('success_message', 'Fotos enviadas com sucesso!');
    }

    public function destroyMidia(Media $media): RedirectResponse
    {
        if ($media->user_id !== auth()->id()) { abort(403); }

        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }
        Storage::disk('public')->delete($media->path);

        $media->delete();
        Cache::flush();
        return back()->with('status', 'gallery-updated')->with('success_message', 'Mídia removida!');
    }

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
            'videos.*' => 'mimetypes:video/mp4,video/quicktime,video/mpeg|max:20480'
        ]);

        $newMediaStatus = $user->acompanhante?->status === 'aprovado' ? 'aprovado' : 'pendente';

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $videoPath = $file->store('galerias/' . $user->id . '/videos', 'public');
                $videoFullPath = storage_path('app/public/' . $videoPath);
                $thumbnailName = pathinfo($videoPath, PATHINFO_FILENAME) . '.jpg';
                $thumbnailRelativePath = 'galerias/' . $user->id . '/thumbnails/' . $thumbnailName;
                $thumbnailFullPath = storage_path('app/public/' . $thumbnailRelativePath);
                Storage::disk('public')->makeDirectory('galerias/' . $user->id . '/thumbnails');
                $ffmpegCommand = "ffmpeg -i \"{$videoFullPath}\" -ss 00:00:01 -vframes 1 \"{$thumbnailFullPath}\"";
                shell_exec($ffmpegCommand);

                if (file_exists($thumbnailFullPath)) {
                    $imagemComMarca = $this->aplicarMarcaDagua($thumbnailFullPath, 'jpg');
                    Storage::disk('public')->put($thumbnailRelativePath, $imagemComMarca);
                }

                Media::create([
                    'user_id' => $user->id,
                    'type' => 'video',
                    'path' => $videoPath,
                    'thumbnail_path' => $thumbnailRelativePath,
                    'status' => $newMediaStatus
                ]);
            }
            Cache::flush();
        }
        return back()->with('status', 'gallery-updated')->with('success_message', 'Vídeos enviados com sucesso!');
    }
}

