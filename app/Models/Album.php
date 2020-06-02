<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'albuns';

    protected $fillable = ['user_id', 'escola_id', 'nome', 'descricao'];

    // SERÁ USADO PARA RELACIONAR TURMAS COM ALBUNS DE FOTO
    public function turmas()
    {
        return $this->belongsToMany(Turma::class , 'album_turmas');
    }
}
