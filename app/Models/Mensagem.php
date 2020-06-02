<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mensagem extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'mensagens';

    protected $fillable = ['user_id', 'assunto', 'mensagem'];

    protected $dates = ['created_at'];

    // SERÁ USADO PARA RELACIONAR MENSAGENS COM ITENS
    public function itens()
    {
        return $this->belongsToMany(User::class, 'itens_mensagem')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
