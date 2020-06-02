<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instituicao extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'instituicoes';

    protected $fillable = ['nome', 'estado', 'cidade', 'endereco', 'cnpj', 'email', 'telfone'];
}
