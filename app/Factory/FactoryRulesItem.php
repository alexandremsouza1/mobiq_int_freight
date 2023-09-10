<?php


namespace App\Factory;

use App\Models\RulesItem;

class FactoryRulesItem
{

  public function create($data)
  {
    $rulesItem = new RulesItem($data);
    if($rulesItem->validate($data)){
      return $rulesItem;
    }
    return false;
  }
  
}