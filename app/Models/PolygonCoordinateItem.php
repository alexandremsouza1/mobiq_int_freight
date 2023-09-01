<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolygonCoordinateItem extends Model
{
    protected $table = 'lf_coordenadas_poligono_item';

    protected $fillable = [
        'id_coordenada',
        'latitude',
        'longitude',
    ];


}