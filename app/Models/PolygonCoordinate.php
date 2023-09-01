<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolygonCoordinate extends Model
{
    protected $table = 'lf_coordenadas_poligono';

    protected $fillable = [
        'id_regra',
        'latitude',
        'longitude',
    ];


}