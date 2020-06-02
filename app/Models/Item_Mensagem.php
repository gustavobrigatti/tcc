<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item_Mensagem extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'itens_mensagem';

    protected $fillable = ['mensagem_id', 'user_id', 'favorito', 'arquivado', 'viewed_at'];

    protected $dates = ['created_at', 'viewed_at', 'deleted_at'];

    public function mensagem()
    {
        return $this->belongsTo(Mensagem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
