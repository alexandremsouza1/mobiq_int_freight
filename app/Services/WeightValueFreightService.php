<?php


namespace App\Services;

use App\Models\Rules;
use App\Repositories\WeightValueDeliveryRepository;

class WeightValueDeliveryService
{



  public function calculatePrice(Rules $rule, $totalWeight)
  {
    $weightValueDelivery = $this->getWeightValueDelivery($rule);
    if(empty($weightValueDelivery)) return 0;
    return $this->deliveryValueByWeight($weightValueDelivery, $totalWeight);
  }

  private function getWeightValueDelivery($rule) : array
  {
    $weightValueDelivery = $rule->weightValueDelivery();
    return $weightValueDelivery->get()->toArray();
  }


  private function deliveryValueByWeight(array $weightValueDeliverys, float $totalWeight) : float
  {
    usort($weightValueDeliverys, function ($a, $b) {
      return $a['peso_minimo'] <=> $b['peso_minimo'];
    });

    foreach ($weightValueDeliverys as $weightValueDelivery) {
      $peso_minimo = floatval($weightValueDelivery['peso_minimo']);
      $peso_maximo = floatval($weightValueDelivery['peso_maximo']);
      if ($totalWeight >= $peso_minimo && $totalWeight <= $peso_maximo) {
        return $weightValueDelivery['valor'];
      }
    }
  }
}
