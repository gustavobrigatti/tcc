<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item_Tarefa extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'itens_tarefas';

    protected $fillable = ['tarefa_id', 'user_id', 'nome', 'path', 'tipo'];

    public function tarefa(){
        return $this->belongsTo(Tarefa::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
