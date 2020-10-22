<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'tarefas';

    protected $fillable = ['turma_id', 'user_id', 'aula_id', 'nome', 'descricao'];

    protected $dates = ['data_entrega'];

    // SERÁ USADO PARA RELACIONAR TURMA COM TAREFA
    public function turma()
    {
        return $this->belongsTo(Turma::class );
    }

    //SERÁ USADO PARA RELACIONAR TAREFA COM O PROFESSOR
    public function user(){
        return $this->belongsTo(User::class);
    }

    //SERÁ USADO PARA RELACIONAR A TAREFA COM A AULA
    public function aula(){
        return $this->belongsTo(Aula::class);
    }

    //SERÁ USADO PARA RELACIONAR A TAREFA COM SEUS ARQUIVOS
    public function itens(){
        return $this->hasMany(Item_Tarefa::class);
    }

    //SERÁ USADO PARA RELACIONAR A TAREFA COM OS COMENTÁRIOS DO PROFESSOR
    public function comentarios(){
        return $this->hasMany(Comentario_Tarefa::class);
    }
}
