<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aula_Turma extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'aula_turma';

    protected $fillable = ['aula_id', 'turma_id', 'user_id', 'hora_inicio', 'hora_fim'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function turma(){
        return $this->belongsTo(Turma::class);
    }
    public function aula(){
        return $this->belongsTo(Aula::class);
    }
}
