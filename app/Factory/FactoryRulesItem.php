<?php


namespace App\Factory;

use App\Models\RulesItem;

class FactoryRulesItem
{

  public function create($data)
  {
    return new RulesItem($data);
  }
  
}