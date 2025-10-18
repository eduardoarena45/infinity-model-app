<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        // Definição de gêneros (mulher vem primeiro)
        $genders = [
            ['slug' => 'mulher', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['slug' => 'homem', 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['slug' => 'trans',  'priority' => '0.6', 'changefreq' => 'weekly'],
        ];

        // Busca todas as cidades
        $cidades = Cidade::all();

        $urls = [];

        foreach ($cidades as $cidade) {
            $cityParam = $this->cityParamFromModel($cidade);

            foreach ($genders as $gender) {
                $urls[] = [
                    'loc' => url("/acompanhantes/{$gender['slug']}/{$cityParam}"),
                    'lastmod' => $cidade->updated_at ?? $cidade->created_at ?? now(),
                    'changefreq' => $gender['changefreq'],
                    'priority' => $gender['priority'],
                ];
            }
        }

        // Cria o XML final
        $xml = $this->renderXml($urls);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function cityParamFromModel($cidade)
    {
        $candidates = ['slug', 'nome', 'name', 'title'];

        foreach ($candidates as $field) {
            if (!empty($cidade->$field)) {
                return Str::slug($cidade->$field, '-');
            }
        }

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
