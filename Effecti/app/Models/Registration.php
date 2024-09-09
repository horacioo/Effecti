<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table="registrations";
    protected $fillable=[
        'nome',
        'cpf',
        'data_nascimento',
        'email',
        'telefone',
        'cep',
        'estado',
        'bairro',
        'cidade',
        'endereco',
        'status'
    ];

}
