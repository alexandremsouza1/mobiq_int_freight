<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    protected $table = 'lf_regras';

    protected $fillable = [
        'id',
        'nome_entrega',
        'tipo',
        'pedido_minimo',
        'valor_frete',
        'horario_corte',
        'horario_atendimento',
        'horario_atendimento_fim',
        'status',
        'qtde_pedido_maxima_frete',
        'tipo_entrega',
        'prazo_dias',
        'prazo_horas',
        'consider_polygon',
    ];


    public function weightValueDelivery()
    {
        return $this->hasMany(WeightValueDelivery::class, 'id_regra', 'id');
    }
    //polygonCoordinate
    public function polygonCoordinate()
    {
        return $this->hasMany(PolygonCoordinate::class, 'id_regra', 'id');
    }

 
}