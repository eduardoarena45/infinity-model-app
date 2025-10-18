<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Acompanhante;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        // Gêneros e configurações SEO
        $genders = [
            ['slug' => 'mulher', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['slug' => 'homem', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['slug' => 'trans',  'priority' => '0.6', 'changefreq' => 'weekly'],
        ];

        $urls = [];

        // === 1. VITRINES POR CIDADE E GÊNERO ===
        $cidades = Cidade::select('id', 'nome')->orderBy('nome')->get();

        foreach ($cidades as $cidade) {
            $citySlug = Str::slug($cidade->nome, '-');

            foreach ($genders as $gender) {
                $urls[] = [
                    'loc' => url("/acompanhantes/{$gender['slug']}/{$citySlug}"),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => $gender['changefreq'],
                    'priority' => $gender['priority'],
                ];
            }
        }

        // === 2. PERFIS INDIVIDUAIS DE ACOMPANHANTES ===
        $acompanhantes = Acompanhante::with('cidade')
            ->where('status', 'ativo') // só perfis ativos
            ->get();

        foreach ($acompanhantes as $acompanhante) {
            $citySlug = $acompanhante->cidade ? Str::slug($acompanhante->cidade->nome, '-') : 'sem-cidade';
            $perfilSlug = Str::slug($acompanhante->nome_artistico ?? 'perfil', '-');

            $urls[] = [
                'loc' => url("/acompanhante/{$acompanhante->id}-{$perfilSlug}"),
                'lastmod' => optional($acompanhante->updated_at)->toAtomString() ?? now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // === 3. MONTA O XML FINAL ===
        $xml = $this->renderXml($urls);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Gera o XML formatado.
     */
    private function renderXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$u['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
