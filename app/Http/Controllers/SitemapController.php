<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        // Gêneros que você usa nas URLs — ajuste se necessário
        $genders = ['mulher', 'homem', 'trans'];

        // Busca todas as cidades (não fazemos alterações no DB)
        $cidades = Cidade::all();

        $urls = [];

        foreach ($cidades as $cidade) {
            // Determina qual atributo da model representa a "parte" da URL da cidade.
            // Tenta atributos comuns: slug, nome, name, title. Se nada, usa id.
            $cityParam = $this->cityParamFromModel($cidade);

            foreach ($genders as $gender) {
                // Se existir rota nomeada, gere com route() (preserva o comportamento da app).
                // Caso contrário, monte manualmente.
                try {
                    if (route('vitrine.show', ['genero' => $gender, 'cidade' => $cityParam], false)) {
                        $loc = route('vitrine.show', ['genero' => $gender, 'cidade' => $cityParam]);
                    } else {
                        $loc = url("/vitrine/{$gender}/{$cityParam}");
                    }
                } catch (\Exception $e) {
                    // Fallback conservador
                    $loc = url("/vitrine/{$gender}/{$cityParam}");
                }

                $urls[] = [
                    'loc' => $loc,
                    // lastmod: se tiver updated_at no model da cidade, use-o; senão, hoje
                    'lastmod' => $cidade->updated_at ?? $cidade->created_at ?? now(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        // Monta XML
        $xml = $this->renderXml($urls);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function cityParamFromModel($cidade)
    {
        // Retorna a melhor propriedade da cidade para usar na URL.
        $candidates = ['slug', 'nome', 'name', 'title'];

        foreach ($candidates as $field) {
            if (isset($cidade->$field) && !empty($cidade->$field)) {
                // Evita espaços e caracteres problemáticos — preserva acentos se sua app usar.
                // Use rawurlencode para não quebrar URLs com espaços.
                // Se sua aplicação usa um formato específico (ex: sem acentos), troque por Str::slug($cidade->$field, '').
                return rawurlencode($cidade->$field);
            }
        }

        // Fallback para id (não ideal, mas seguro)
        return $cidade->id;
    }

    private function renderXml(array $urls)
    {
        $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xmlHeader .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $body = '';
        foreach ($urls as $u) {
            $body .= "  <url>\n";
            $body .= "    <loc>{$this->xmlEscape($u['loc'])}</loc>\n";
            $body .= "    <lastmod>{$this->xmlEscape($u['lastmod']->toAtomString())}</lastmod>\n";
            $body .= "    <changefreq>{$this->xmlEscape($u['changefreq'])}</changefreq>\n";
            $body .= "    <priority>{$this->xmlEscape($u['priority'])}</priority>\n";
            $body .= "  </url>\n";
        }

        $xmlFooter = '</urlset>';

        return $xmlHeader . $body . $xmlFooter;
    }

    private function xmlEscape($string)
    {
        return htmlspecialchars((string) $string, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}
