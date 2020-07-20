<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'turmas';

    protected $fillable = ['nome', 'periodo'];

    // SERÁ USADO PARA RELACIONAR ALUNOS COM CLASSES DE AULA
    public function alunos()
    {
        return $this->belongsToMany(User::class , 'alunos_turma');
    }

    public function aulas(){
        return $this->hasMany(Aula_Turma::class);
    }
}
