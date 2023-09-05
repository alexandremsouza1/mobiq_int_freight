<?php



namespace App\Services;

use App\Factory\FactoryRulesItem;
use App\Integrations\Source;
use App\Models\Rules;
use Carbon\Carbon;
use Faker\Core\Coordinates;

class RulesItemService
{

  private $source;

  private $factoryRulesItem;

  private $weightValueFreightService;

  private $coordinatesService;

  private $coordinates;

  public function __construct(
    Source $source, 
    FactoryRulesItem $factoryRulesItem, 
    WeightValueFreightService $weightValueFreightService,
    CoordinatesService $coordinatesService
    )
  {
    $this->source = $source;
    $this->factoryRulesItem = $factoryRulesItem;
    $this->weightValueFreightService = $weightValueFreightService;
    $this->coordinatesService = $coordinatesService;
  }


  public function hasTimePassed($specificTime)
  {
    $currentTime = Carbon::now('America/Sao_Paulo');
    $specificDateTime = Carbon::parse($specificTime)->setTimezone('America/Sao_Paulo');
    return $currentTime->greaterThan($specificDateTime);
  }


  public function calculateBusinessDay(int $daysToAdd, ?Carbon $currentDate = null)
  {
      if ($currentDate === null) {
          $currentDate = Carbon::now();
      }
      $holidays = $this->source->getConsultarFeriados($currentDate->format('Y-m-d'), $currentDate->addWeekdays($daysToAdd)->format('Y-m-d'));

      $deliveryDate = $currentDate->copy()->addWeekdays($daysToAdd);
  
      foreach ($holidays as $holiday) {
          if ($deliveryDate->isSameDay($holiday)) {
              $deliveryDate->addWeekday();
              return $this->calculateBusinessDay(0, $deliveryDate);
          }
      }
  
      return $deliveryDate->format('Y-m-d H:i:s');
  }


  public function getRulesItem(Rules $rule)
  {
      $dias = $rule->prazo_dias;
      $horarioCorte = $rule->horario_corte;
      if ($this->hasTimePassed($rule->horario_corte)) {
        $dias = intval($dias) + 1;
        $horarioCorte = null;
      }
      
      $deliveryDate = $this->calculateBusinessDay($dias);


      $previsaoEntrega = $deliveryDate;
      $previsaoEntregaSap = $deliveryDate;

      $price = $this->weightValueFreightService->calculatePrice($rule);

      $checkInPolygon = $this->coordinatesService->checkInPolygon($rule, $this->getCoordinates());


      $validateQuantityOrders = $this->validateQuantityOrders($r, $quantidade);

      if ($checkInPolygon == false && $rule->consider_polygon === '1') {
         return [];
      }
      if ($validateQuantityOrders) {
        $tipoEntrega = isset($deliveryTypes[$rule->tipo_entrega]) ? $deliveryTypes[$rule->tipo_entrega] : $deliveryTypes[0];
        if ($tipo == 1) {
          if (isset($pesoJson) && count($pesoJson) > 0) {
            $return[] = [
              'weight'                    => $pesoJson,
              'coordinates'               => $coordenadasJson,
              'deliveryName'             => $rule->nome_entrega,
              'deliveryEstimate'         => $previsaoEntrega,
              'deliveryEstimateSap'     => $previsaoEntregaSap,
              'id'                        => $rule->id,
              'deliveryTypeId'          => $rule->id_tipo_entrega,
              'businessHours'            => $rule->horario_atendimento,
              'businessHoursEnd'        => $rule->horario_atendimento_fim,
              'minimumOrder'             => null,
              'key'                       => $tipoEntrega,
              'cutOffTime'              => $horarioCorte
            ];
          } else {
            $return[] = [
              'weight'                    => [
                [
                  'minimum_weight'    => 0,
                  'maximum_weight'    => 1000000000000000000,
                  'value'             => $rule->valor_frete
                ]
              ],
              'coordinates'               => $coordenadasJson,
              'deliveryName'             => $rule->nome_entrega,
              'deliveryEstimate'         => $previsaoEntrega,
              'deliveryEstimateSap'     => $previsaoEntregaSap,
              'id'                        => $rule->id,
              'deliveryTypeId'          => $rule->tipo_entrega,
              'businessHours'            => $rule->horario_atendimento,
              'businessHoursEnd'        => $rule->horario_atendimento_fim,
              'minimumOrder'             => (int)($rule->pedido_minimo * 100),
              'deliveryFee'              => $rule->valor_frete,
              'key'                       => $tipoEntrega,
              'cutOffTime'              => $horarioCorte
            ];
          }
        } else {
          $return[] = [
            'coordinates'               => $coordenadasJson,
            'deliveryName'             => $rule->nome_entrega,
            'deliveryEstimate'         => $previsaoEntrega,
            'deliveryEstimateSap'     => $previsaoEntregaSap,
            'id'                        => $rule->id,
            'deliveryTypeId'          => $rule->tipo_entrega,
            'businessHours'            => $rule->horario_atendimento,
            'businessHoursEnd'        => $rule->horario_atendimento_fim,
            'minimumOrder'             => (int)($rule->pedido_minimo * 100),
            'deliveryFee'              => $rule->valor_frete,
            'key'                       => $tipoEntrega,
            'cutOffTime'              => $horarioCorte
          ];
        }
      }

      //aqui deve retornar FactoryRulesItem
  }

  /**
   * Get the value of coordinates
   */ 
  public function getCoordinates()
  {
    return $this->coordinates;
  }

  /**
   * Set the value of coordinates
   *
   * @return  self
   */ 
  public function setCoordinates($coordinates)
  {
    $this->coordinates = $coordinates;

    return $this;
  }
}
