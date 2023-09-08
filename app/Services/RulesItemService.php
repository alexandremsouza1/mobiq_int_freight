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
    CoordinatesService $coordinatesService,
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


  public function getRulesItem(Rules $rule)
  {
      $days = $rule->prazo_dias;
      $deadline = $rule->horario_corte;
      if ($this->hasTimePassed($rule->horario_corte)) {
        $days = intval($days) + 1;
        $deadline = null;
      }
      
      $deliveryDate = $this->calculateBusinessDay($days);


      $previsaoEntrega = $deliveryDate;

      $price = $this->weightValueFreightService->calculatePrice($rule);

      $checkInPolygon = $this->coordinatesService->checkInPolygon($rule, $this->getCoordinates());



      if ($checkInPolygon == false && $rule->consider_polygon === '1') {
         return [];
      }

      return $this->factoryRulesItem->create([
        'label' => $rule->label,
        'key' => $rule->key,
        'price' => $price,
        'deliveryDate' => $previsaoEntrega,
        'deadline' => $deadline,
      ])->toArray();
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
