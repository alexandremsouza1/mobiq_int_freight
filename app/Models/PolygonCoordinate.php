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

    //polygonCoordinateItem
    public function polygonCoordinateItem()
    {
        return $this->hasMany(PolygonCoordinateItem::class, 'id_coordenada', 'id');
    }

    //rules
    public function rules()
    {
        return $this->belongsTo(Rules::class, 'id_regra', 'id');
    }


}