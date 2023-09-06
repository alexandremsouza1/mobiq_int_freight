<?php

namespace Tests\Unit\Services;

use App\Factory\FactoryCreditLimitDto;
use App\Integrations\Source;
use App\Models\Rules;
use App\Services\CartService;
use App\Services\FreightService;
use App\Services\OrdersService;
use App\Services\RulesItemService;
use App\Services\RulesService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;


class FreightServiceTest extends TestCase
{

    protected $service;



    protected function setUp(): void
    {
      parent::setUp();

      $factory = $this->mockFactory();
      $cartService = $this->mockCartService();
      $rulesService = $this->mockRulesService();
      $rulesItemService = $this->mockRulesItemService();
      $ordersService = $this->mockOrdersService();
      
      $this->service = new FreightService(
        $factory,
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
      $cartService = Mockery::mock(CartService::class);
      //mock the method getCart
      $cartData = (object)[
        'id' => 1,
        'clientId' => '0068000249',
        'items' => [
            ['id' => 1, 'product' => 'Product A', 'quantity' => 2],
            ['id' => 2, 'product' => 'Product B', 'quantity' => 1],
        ],
        'total' => 15000,
      ];
      $cartService->shouldReceive('getCart')->andReturn($cartData);
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
      $rulesItemService = Mockery::mock(RulesItemService::class)->makePartial();
      return $rulesItemService;
    }

    public function mockOrdersService()
    {
      $ordersService = Mockery::mock(OrdersService::class)->makePartial();
      //mock the method validateQuantityOrders
      $ordersService->shouldReceive('getQuantityOrders')->andReturn(0);
      return $ordersService;
    }


    public function testGetFreight()
    {
      $data = $this->service->getFreight('2ca77b85-5c26-4874-bc67-426b4886498d');
      $this->assertEquals('',json_encode($data));
    }
    
    
}
