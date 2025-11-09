<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        return response()->json(Paciente::all());
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'sobrenome' => 'nullable|string|max:255',
        'cpf' => 'required|string|max:11|unique:pacientes,cpf',
        'data_nascimento' => 'required|date',
        'telefone' => 'nullable|string|max:20',
        'endereco' => 'nullable|string|max:255',
    ]);

    $paciente = Paciente::create($validated);
    return response()->json($paciente, 201);
}

    public function show($id)
    {
        $paciente = Paciente::with('atendimentos')->find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado'], 404);
        }

        return response()->json($paciente);
    }

    public function update(Request $request, $id)
{
    $paciente = Paciente::find($id);

    if (!$paciente) {
        return response()->json(['message' => 'Paciente não encontrado'], 404);
    }

    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'sobrenome' => 'nullable|string|max:255',
        'cpf' => 'required|string|max:14|unique:pacientes,cpf,' . $id,
        'data_nascimento' => 'required|date',
        'telefone' => 'nullable|string|max:20',
        'endereco' => 'nullable|string|max:255',
    ]);

    $paciente->update($validated);
    return response()->json($paciente);
}

    public function destroy($id)
    {
        $paciente = Paciente::find($id);

        if (!$paciente) {
            return response()->json(['message' => 'Paciente não encontrado'], 404);
        }

        $paciente->delete();

        return response()->json(['message' => 'Paciente removido com sucesso']);
    }
}
