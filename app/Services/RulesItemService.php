<?php



namespace App\Services;

use App\DTO\CartDto;
use App\Factory\FactoryRulesItem;
use App\Factory\FactoryCreditLimitDto;
use App\DTO\CreditLimitDto;
use App\Integrations\Source;
use App\Models\Rules;
use Carbon\Carbon;
use App\Enums\DeliveryTypes;

class RulesItemService
{

  private $source;

  private $factoryRulesItem;

  private $weightValueFreightService;

  private $coordinatesService;

  private $clientId;


  /** @var FactoryCreditLimitDto $factory */
  protected $factory;

  /** @var CreditLimitDto $creditLimitDto */
  private $creditLimitDto;

  public function __construct(
    Source $source, 
    FactoryRulesItem $factoryRulesItem, 
    WeightValueFreightService $weightValueFreightService,
    CoordinatesService $coordinatesService,
    FactoryCreditLimitDto $factory,
    )
  {
    $this->source = $source;
    $this->factoryRulesItem = $factoryRulesItem;
    $this->weightValueFreightService = $weightValueFreightService;
    $this->coordinatesService = $coordinatesService;
    $this->factory = $factory;
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
      $endDate = $currentDate->copy()->addDay($daysToAdd);
      $holidays = $this->source->getConsultarFeriados($currentDate->format('Y-m-d'), $endDate->format('Y-m-d'));

      $deliveryDate = $currentDate->copy()->addDay($daysToAdd);
  
      foreach ($holidays as $holiday) {
        if ($holiday['Type'] != 'E') {
          $holiday_date = Carbon::createFromFormat('d/m/Y', $holiday['Feriado']);
          if ($holiday_date !== false) {
            if ($deliveryDate->isSameDay($holiday_date)) {
                $deliveryDate->addDay();
                return $this->calculateBusinessDay(0, $deliveryDate);
            }
          }
        }
      }
  
      return $deliveryDate->format('Y-m-d H:i:s');
  }


  public function getRulesItem(Rules $rule,CartDto $cart)
  {
      $days = $rule->prazo_dias;
      $deadline = $rule->horario_corte;
      if ($this->hasTimePassed($rule->horario_corte)) {
        $days = intval($days) + 1;
        $deadline = null;
      }
      
      $deliveryDate = $this->calculateBusinessDay($days);


      $previsaoEntrega = $deliveryDate;

      $price = $this->weightValueFreightService->calculatePrice($rule, $cart->getTotalWeight());

      $checkInPolygon = $this->coordinatesService->checkInPolygon($rule, $this->getCoordinates());



      if ($checkInPolygon == false && $rule->consider_polygon === '1') {
         return [];
      }

      return $this->factoryRulesItem->create([
        'label' => $rule->nome_entrega,
        'key' => DeliveryTypes::getKey($rule->tipo_entrega),
        'price' => $price,
        'deliveryDate' => $previsaoEntrega,
        'deadline' => $deadline,
      ])->toArray();
  }

  public function fillEntregaConvencional($totalPrice)
  {
      $this->creditLimitDto = $this->factory->createCreditLimitDto($this->clientId);
      $now = Carbon::now();
      $data_previsao_entrega_convencional = null;
      $horarioCorte = $this->creditLimitDto->getCutoffTime();
      list($hours, $minutes, $seconds) = explode(':', $horarioCorte);
  
      $dataVisita = Carbon::parse($this->creditLimitDto->getVisitDate())->addHours(3);
      $dataVisita->setTime($hours, $minutes, $seconds);
  
      if ($now < $dataVisita) {
          $data_previsao_entrega_convencional = Carbon::parse($this->creditLimitDto->getDeliveryOption1())->addHours(3);
      } else {
          $data_previsao_entrega_convencional = Carbon::parse($this->creditLimitDto->getDeliveryOption2())->addHours(3);
      }
      if($totalPrice >= $this->creditLimitDto->getMinimumOrder()) {
        return $this->factoryRulesItem->create([
          'label' => 'Entrega Convencional',
          'key' => DeliveryTypes::getKey(0),
          'price' => 0,
          'deliveryDate' => $data_previsao_entrega_convencional,
          'deadline' => $horarioCorte,
        ])->toArray();
      }
  }



  /**
   * Get the value of coordinates
   */ 
  public function getCoordinates()
  {
    return $this->creditLimitDto->getCoordinates();
  }


  /**
   * Set the value of clientId
   *
   * @return  self
   */ 
  public function setClientId($clientId)
  {
    $this->clientId = $clientId;

    return $this;
  }
}
