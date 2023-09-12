<?php

namespace Tests\Feature;

use App\Models\PolygonCoordinate;
use App\Models\PolygonCoordinateItem;
use App\Models\Rules;
use App\Models\WeightValueDelivery;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueDeliveryRepository;
use App\Services\WeightValueDeliveryService;
use App\Services\RulesService;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Mockery;
use Tests\TestCase;


class WeightValueDeliveryServiceTest extends TestCase
{

    protected $service;



    protected function setUp(): void
    {
      $this->service = $this->mockRulesItemService();
      parent::setUp();
    }

    public function mockRulesItemService()
    {
      $weightValueDeliveryService = new WeightValueDeliveryService();
      return $weightValueDeliveryService;
    }



    public function testUpdateDeliveryValueByWeight()
    {
      $rules = new Rules();
      $rulesRepository = new RulesRepository($rules);

      $weightValueDelivery  = new WeightValueDelivery();
      $weightValueDeliveryRepository = new WeightValueDeliveryRepository($weightValueDelivery);
      
      $polygonCoordinate = new PolygonCoordinate();
      $polygonCoordinateRepository = new PolygonCoordinateRepository($polygonCoordinate);
      
      $polygonCoordinateItem = new PolygonCoordinateItem();
      $polygonCoordinateItemRepository = new PolygonCoordinateItemRepository($polygonCoordinateItem);

      $ruleService = new RulesService(
        $rulesRepository,
        $weightValueDeliveryRepository,
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