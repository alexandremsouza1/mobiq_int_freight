<?php

namespace Tests\Unit\Services;

use App\Factory\FactoryCart;
use App\Factory\FactoryCreditLimitDto;
use App\Factory\FactoryRulesItem;
use App\Integrations\Source;
use App\Integrations\SourceCart;
use App\Models\Rules;
use App\Services\CartService;
use App\Services\CoordinatesService;
use App\Services\DeliveryService;
use App\Services\OrdersService;
use App\Services\RulesItemService;
use App\Services\RulesService;
use App\Services\WeightValueDeliveryService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;


class DeliveryServiceTest extends TestCase
{

    protected $service;



    protected function setUp(): void
    {
      parent::setUp();
      $cartService = $this->mockCartService();
      $rulesService = $this->mockRulesService();
      $rulesItemService = $this->mockRulesItemService();
      $ordersService = $this->mockOrdersService();
      
      $this->service = new DeliveryService(
        $cartService,
        $rulesService,
        $rulesItemService,
        $ordersService
      );
    }



    public function mockFactory()
    {
      $source = Mockery::mock(Source::class);
      //$source = new Source();
      $credit_limit_json = file_get_contents(__DIR__ . '/input/credit_limit.json');
      $source->shouldReceive('getCreditLimit')->andReturn(json_decode($credit_limit_json,true));
      $factory = new FactoryCreditLimitDto($source);
      return $factory;
    }

    public function mockCartService()
    {
      $sourceCart = Mockery::mock(SourceCart::class);
      //mock the method getCart
      $cartData = [
        'id' => 1,
        'clientId' => '0068000249',
        'items' => [
            ['id' => 1, 'product' => 'Product A', 'quantity' => 2, 'weight' => 0.5, 'price' => 5000],
            ['id' => 2, 'product' => 'Product B', 'quantity' => 1, 'weight' => 1.0, 'price' => 1000],
        ],
      ];
      $sourceCart->shouldReceive('getCart')->andReturn($cartData);
      $cartService = new CartService($sourceCart);
      return $cartService;
    }

    public function mockRulesService()
    {
      $rulesService = Mockery::mock(RulesService::class);
      $rulesData = file_get_contents(__DIR__ . '/input/rules.json');
      $rules = json_decode($rulesData,true);
      $rulesModel = [];
      foreach ($rules as $rule) {
        $rulesModel[] = new Rules($rule);
      }
      
      $collection = new Collection($rulesModel);

      $rulesService->shouldReceive('getRules')->andReturn($collection);
      return $rulesService;
    }

    public function mockRulesItemService()
    {
      $source = Mockery::mock(Source::class);
      $holydays = file_get_contents(__DIR__ . '/input/consultar_feriado.json');
      $source->shouldReceive('getConsultarFeriados')->andReturn(json_decode($holydays,true));
      $factoryRulesItem = Mockery::mock(FactoryRulesItem::class)->makePartial();
      $weightValueDeliveryService = Mockery::mock(WeightValueDeliveryService::class)->makePartial();
      $coordinatesService = Mockery::mock(CoordinatesService::class)->makePartial();
      $factory = $this->mockFactory();
      $rulesItemService = new RulesItemService(
        $source,
        $factoryRulesItem,
        $weightValueDeliveryService,
        $coordinatesService,
        $factory
      );
      return $rulesItemService;
    }

    public function mockOrdersService()
    {
      $ordersService = Mockery::mock(OrdersService::class)->makePartial();
      //mock the method validateQuantityOrders
      $ordersService->shouldReceive('getQuantityOrders')->andReturn(0);
      return $ordersService;
    }


    public function testGetDelivery()
    {
      $data = $this->service->getDelivery('2ca77b85-5c26-4874-bc67-426b4886498d');
      $this->assertEquals('',json_encode($data));
    }
    
    
}
