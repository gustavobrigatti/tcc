<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comentario_Tarefa extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'comentarios_tarefa';

    protected $fillable = ['tarefa_id', 'user_id', 'comentario'];

    public function tarefa(){
        return $this->belongsTo(Tarefa::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
