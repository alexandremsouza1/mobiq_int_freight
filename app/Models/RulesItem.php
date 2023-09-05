<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RulesItem extends Model
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
      'deadline' => 'datetime H:i',
      'price' => 'integer',
    ];

    public function rules()
    {
      return [
        'label' => 'required|string',
        'key' => 'required|string',
        'price' => 'required|integer',
        'deliveryDate' => 'required|date',
        'deadline' => 'required|date_format:H:i',
      ];
    }

}
 