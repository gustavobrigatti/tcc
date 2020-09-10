<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arquivo extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'arquivos';

    protected $fillable = ['turma_id', 'user_id', 'nome'];

    // SERÁ USADO PARA RELACIONAR TURMA COM PASTA DE ARQUIVO
    public function turma()
    {
        return $this->belongsTo(Turma::class );
    }

    //SERÁ USADO PARA RELACIONAR TURMA COM O PROFESSOR
    public function user(){
        return $this->belongsTo(User::class);
    }

    //SERÁ USADO PARA RELACIONAR A PASTA DE ARQUIVO COM OS ITENS
    public function itens(){
        return $this->hasMany(Item_Arquivo::class);
    }
}
