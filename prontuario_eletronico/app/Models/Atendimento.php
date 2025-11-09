<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'hipotese_diagnostica',
        'cid',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
