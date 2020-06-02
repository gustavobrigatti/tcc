<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Hashid;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable, Hashid, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'data_nascimento', 'cpf', 'escola_id', 'role', 'estado', 'cidade', 'endereco', 'telefone'];

    protected $dates = ['data_nascimento'];

    protected $hidden = ['password', 'remember_token',];

    protected $casts = ['email_verified_at' => 'datetime',];

    public function setDataNascimentoAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['data_nascimento'] = NULL;
        } else {
            $this->attributes['data_nascimento'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'escola_id');
    }

    // SERÁ USADO PARA RELACIONAR ALUNOS COM CLASSES DE AULA
    public function aluno_turmas()
    {
        return $this->belongsToMany(Turma::class, 'alunos_turma', 'user_id', 'turma_id');
    }

    // SERÁ USADO PARA RELACIONAR PROFESSORES COM CLASSES DE AULA
    public function professor_turmas()
    {
        return $this->belongsToMany(Turma::class, 'professores_turma', 'user_id', 'turma_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class, 'alunos_responsaveis', 'responsavel_id', 'aluno_id');
    }

    public function responsaveis()
    {
        return $this->belongsToMany(User::class, 'alunos_responsaveis', 'aluno_id', 'responsavel_id');
    }
}
