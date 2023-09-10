<?php

namespace App\Models;


class RulesItem extends BaseModel
{

    protected $fillable = [
      'label',
      'key',
      'price',
      'deliveryDate',
      'deadline',
    ];

    protected $casts = [
      'deliveryDate' => 'datetime',
      'deadline' => 'datetime',
      'price' => 'integer',
    ];

    public function rules()
    {
      return [
        'label' => 'required|string',
        'key' => 'required|string',
        'price' => 'required|integer',
        'deliveryDate' => 'required|date',
        'deadline' => 'required|date_format:H:i:s',
      ];
    }

}
 