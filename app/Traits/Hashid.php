<?php
/**
 * Created by PhpStorm.
 * User: andersonfranco
 * Date: 2019-04-06
 * Time: 16:53
 */

namespace App\Traits;


use Vinkla\Hashids\Facades\Hashids;

trait Hashid
{

    // https://tighten.co/blog/laravel-tip-bootable-model-traits

    /*
    public static function bootHashid()
    {
        static::creating(function ($model) {
            //$model->uuid = Uuid::uuid4();
        });
    }
    */

    public function getHashIdAttribute()
    {
        return Hashids::encode($this->id);
    }

    public function getRouteKey()
    {
        return $this->hash_id;
    }

}