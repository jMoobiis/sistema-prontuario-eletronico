<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'sobrenome',
        'cpf',
        'data_nascimento',
        'telefone',
        'endereco',
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
}
