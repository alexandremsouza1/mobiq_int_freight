<?php


namespace App\Services;

use App\Models\Rules;
use App\Repositories\WeightValueFreightRepository;

class WeightValueFreightService
{



  public function calculatePrice(Rules $rule, $totalWeight)
  {
    $weightValueFreight = $this->getWeightValueFreight($rule);
    if(empty($weightValueFreight)) return 0;
    return $this->deliveryValueByWeight($weightValueFreight, $totalWeight);
  }

  private function getWeightValueFreight($rule) : array
  {
    $weightValueFreight = $rule->weightValueFreight();
    return $weightValueFreight->get()->toArray();
  }


  private function deliveryValueByWeight(array $weightValueFreights, float $totalWeight) : float
  {
    usort($weightValueFreights, function ($a, $b) {
      return $a['peso_minimo'] <=> $b['peso_minimo'];
    });

    foreach ($weightValueFreights as $weightValueFreight) {
      $peso_minimo = floatval($weightValueFreight['peso_minimo']);
      $peso_maximo = floatval($weightValueFreight['peso_maximo']);
      if ($totalWeight >= $peso_minimo && $totalWeight <= $peso_maximo) {
        return $weightValueFreight['valor'];
      }
    }
  }
}
