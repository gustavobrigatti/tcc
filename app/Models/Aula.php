<?php

namespace App\Models;

use App\Traits\Hashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aula extends Model
{
    use Hashid, SoftDeletes;

    protected $table = 'aulas';

    protected $fillable = ['escola_id', 'nome'];
}
