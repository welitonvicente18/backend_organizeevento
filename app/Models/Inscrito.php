<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'id_eventos', 'cpf', 'sobrenome', 'data_nascimento', 'telefone', 'email', 'cidade', 'endereco', 'cep'];
}
