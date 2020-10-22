<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'medias';

    protected $fillable = ['turma_id', 'user_id', 'aula_id', 'media'];
}
