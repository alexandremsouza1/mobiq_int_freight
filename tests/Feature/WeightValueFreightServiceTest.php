<?php

namespace Tests\Feature;

use App\Models\PolygonCoordinate;
use App\Models\PolygonCoordinateItem;
use App\Models\Rules;
use App\Models\WeightValueFreight;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueFreightRepository;
use App\Services\WeightValueFreightService;
use App\Services\RulesService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Mockery;
use Tests\TestCase;


class WeightValueFreightServiceTest extends TestCase
{

    protected $service;



    protected function setUp(): void
    {
      $this->service = $this->mockRulesItemService();
      parent::setUp();
    }

    public function mockRulesItemService()
    {
      $weightValueFreightService = new WeightValueFreightService();
      return $weightValueFreightService;
    }



    public function testUpdateDeliveryValueByWeight()
    {
      $rules = new Rules();
      $rulesRepository = new RulesRepository($rules);

      $weightValueFreight  = new WeightValueFreight();
      $weightValueFreightRepository = new WeightValueFreightRepository($weightValueFreight);
      
      $polygonCoordinate = new PolygonCoordinate();
      $polygonCoordinateRepository = new PolygonCoordinateRepository($polygonCoordinate);
      
      $polygonCoordinateItem = new PolygonCoordinateItem();
      $polygonCoordinateItemRepository = new PolygonCoordinateItemRepository($polygonCoordinateItem);

      $ruleService = new RulesService(
        $rulesRepository,
        $weightValueFreightRepository,
        $polygonCoordinateRepository,
        $polygonCoordinateItemRepository
      );

      $rules = $ruleService->getRules();
      $rule = null;
      foreach($rules as $key => $rules) {
        if($rules->id == 101) {
          $rule = $rules;
          break;
        }
      }
      $weight = $this->service->calculatePrice($rule, 1600.50);
      $this->assertEquals(50, $weight);
    }

}