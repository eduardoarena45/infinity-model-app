<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DisponibilidadeController extends Controller
{
    /**
     * Mostra o formulário para editar a disponibilidade.
     */
    public function edit(Request $request): View
    {
        $acompanhante = $request->user()->acompanhante;
        $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

        return view('profile.disponibilidade', [
            'horarios' => $acompanhante->horarios_atendimento ?? [],
            'diasSemana' => $diasSemana,
        ]);
    }

    /**
     * Atualiza a disponibilidade no banco de dados.
     */
    public function update(Request $request): RedirectResponse
    {
        // --- INÍCIO DA NOVA LÓGICA DE VALIDAÇÃO ---
        $regras = [];
        $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];

        foreach ($diasSemana as $dia) {
            // Se a checkbox 'ativo' para este dia foi marcada...
            if ($request->has("ativo.{$dia}")) {
                // ...então o campo de horário para este dia torna-se obrigatório.
                $regras["horario.{$dia}"] = 'required|string|max:255';
            }
        }

        // Adiciona mensagens de erro personalizadas em português
        $mensagens = [
            'horario.*.required' => 'O campo de horário é obrigatório quando o dia está selecionado.',
        ];

        $request->validate($regras, $mensagens);
        // --- FIM DA NOVA LÓGICA DE VALIDAÇÃO ---

        $acompanhante = $request->user()->acompanhante;
        
        $horarios = [];
        foreach ($diasSemana as $dia) {
            if ($request->has("ativo.{$dia}")) {
                $horarios[$dia] = [
                    'ativo' => true,
                    // Usamos o dado já validado
                    'horario' => $request->input("horario.{$dia}"),
                ];
            }
        }

        $acompanhante->update(['horarios_atendimento' => $horarios]);

        return back()->with('status', 'disponibilidade-updated');
    }
}
