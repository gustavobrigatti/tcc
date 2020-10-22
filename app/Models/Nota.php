<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'notas';

    protected $fillable = ['turma_id', 'user_id', 'aula_id', 'nome', 'nota'];

    // SERÁ USADO PARA RELACIONAR NOTA COM TAREFA
    public function turma()
    {
        return $this->belongsTo(Turma::class );
    }

    //SERÁ USADO PARA RELACIONAR NOTA COM O PROFESSOR
    public function user(){
        return $this->belongsTo(User::class);
    }

    //SERÁ USADO PARA RELACIONAR A NOTA COM A AULA
    public function aula(){
        return $this->belongsTo(Aula::class);
    }
}
