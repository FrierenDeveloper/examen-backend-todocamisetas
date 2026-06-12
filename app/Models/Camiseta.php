<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camiseta extends Model
{public function tallas()
{
    return $this->belongsToMany(Talla::class, 'camiseta_talla');
}
    //
}
