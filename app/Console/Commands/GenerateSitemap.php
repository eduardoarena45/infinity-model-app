<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cidade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    /**
     * O nome do comando (executado via terminal)
     */
    protected $signature = 'sitemap:generate';

    /**
     * Descrição que aparece em "php artisan list"
     */
    protected $description = 'Gera o arquivo sitemap.xml estático em /public';

    /**
     * Executa o comando
     */
    public function handle()
    {
        $this->info('🧭 Gerando sitemap.xml...');

        $genders = ['mulher', 'homem', 'trans'];
        $cidades = Cidade::all();

        $urls = [];

        foreach ($cidades as $cidade) {
            $cityParam = $this->cityParamFromModel($cidade);

            foreach ($genders as $gender) {
                $loc = url("/acompanhantes/{$gender}/{$cityParam}");
                $urls[] = [
                    'loc' => $loc,
                    'lastmod' => $cidade->updated_at ?? $cidade->created_at ?? now(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }

        $xml = $this->renderXml($urls);

        $path = public_path('sitemap.xml');
        File::put($path, $xml);

        $this->info('✅ sitemap.xml gerado com sucesso em: ' . $path);
        return 0;
    }

    private function cityParamFromModel($cidade)
    {
        $candidates = ['slug', 'nome', 'name', 'title'];
        foreach ($candidates as $field) {
            if (isset($cidade->$field) && !empty($cidade->$field)) {
                // Se suas rotas usam acentos, mantenha rawurlencode; se usam slugs, use Str::slug.
                return rawurlencode($cidade->$field);
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
