<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightValueDelivery extends Model
{
    protected $table = 'lf_peso_valor_frete';

    protected $fillable = [
        'id_regra',
        'peso_minimo',
        'peso_maximo',
        'valor',
    ];

    //rules
    public function rules()
    {
        return $this->belongsTo(Rules::class, 'id_regra', 'id');
    }

  
}