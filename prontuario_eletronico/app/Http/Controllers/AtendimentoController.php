<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = Atendimento::with('paciente')->get();
        return response()->json($atendimentos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'hipotese_diagnostica' => 'required|string|max:255',
            'cid' => 'nullable|string|max:10',
        ]);

        $atendimento = Atendimento::create($validated);

        return response()->json($atendimento, 201);
    }

    public function show($id)
    {
        $atendimento = Atendimento::with('paciente')->find($id);

        if (!$atendimento) {
            return response()->json(['message' => 'Atendimento não encontrado'], 404);
        }

        return response()->json($atendimento);
    }

    public function update(Request $request, $id)
    {
        $atendimento = Atendimento::find($id);

        if (!$atendimento) {
            return response()->json(['message' => 'Atendimento não encontrado'], 404);
        }

        $validated = $request->validate([
            'hipotese_diagnostica' => 'required|string|max:255',
            'cid' => 'nullable|string|max:10',
        ]);

        $atendimento->update($validated);

        return response()->json($atendimento);
    }

    public function destroy($id)
    {
        $atendimento = Atendimento::find($id);

        if (!$atendimento) {
            return response()->json(['message' => 'Atendimento não encontrado'], 404);
        }

        $atendimento->delete();

        return response()->json(['message' => 'Atendimento excluído com sucesso']);
    }
}
