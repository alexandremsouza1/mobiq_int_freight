<?php


namespace App\Services;

use App\Models\Rules;
use App\Repositories\WeightValueFreightRepository;

class WeightValueFreightService
{

  private $weightValueFreightRepository;

  private $valor_frete_expresso = 0;

  private $peso_total = 0;

  public function __construct(WeightValueFreightRepository $weightValueFreightRepository)
  {
    $this->weightValueFreightRepository = $weightValueFreightRepository;
  }


  public function calculatePrice(Rules $rule)
  {
    $weightValueFreight = $this->getWeightValueFreight($rule);
    if(!$rule->pedido_minimo) {
      $this->updateDeliveryValueByWeight($rule, $weightValueFreight);
    }
  }

  private function getWeightValueFreight($rule) : array
  {
    $weightValueFreight = $rule->weightValueFreight();
    return $weightValueFreight->get()->toArray();
  }


  private function updateDeliveryValueByWeight($rule, $weightValueFreight)
  {
    if (!$weightValueFreight || !$weightValueFreight->peso || count($weightValueFreight->peso) === 0) {
      $rule->valor = 0;
      return;
    }
    if (count($weightValueFreight->peso) === 1) {
      $this->valor_frete_expresso = $weightValueFreight->peso[0]->valor;
    } else {
      $this->valor_frete_expresso = 'undefined';
      $tam = count($weightValueFreight->peso);
      for ($i = 0; $i < $tam; $i++) {
        $peso = $weightValueFreight->peso[$i];
        if ($this->peso_total <= $peso->peso_maximo) {
          $this->valor_frete_expresso = $peso->valor;
          break;
        }
      }
      //Se o peso do pedido for superior a todos valores de peso, considerar último elemento
      if ($this->valor_frete_expresso === 'undefined') {
        // this.valor_frete_expresso = 0;
        $peso = $weightValueFreight->peso[$tam - 1];
        $this->valor_frete_expresso = $peso->valor;
        $weightValueFreight->offWeight = true;
      } else {
        $weightValueFreight->offWeight = false;
      }
    }

    //Se frete for diferente do convencional
    if ($weightValueFreight->id) {
      $weightValueFreight->valor = $this->valor_frete_expresso;
    }
    //Frete igual ao convencional
    else {
      $weightValueFreight->valor = 0;
    }
  }
}
