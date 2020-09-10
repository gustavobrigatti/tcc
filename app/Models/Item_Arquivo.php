<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item_Arquivo extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'itens_arquivos';

    protected $fillable = ['arquivo_id', 'user_id', 'nome', 'path'];

    // SERÁ USADO PARA RELACIONAR O ITEM COM PASTA DE ARQUIVO
    public function arquivo()
    {
        return $this->belongsTo(Arquivo::class );
    }

    //SERÁ USADO PARA RELACIONAR ITEM COM O USUÁRIO QUE ENVIOU
    public function user(){
        return $this->belongsTo(User::class);
    }
}
