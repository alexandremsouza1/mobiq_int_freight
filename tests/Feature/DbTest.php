<?php

namespace Tests\Feature;

use App\Models\Rules;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueDeliveryRepository;
use App\Services\RulesService;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;


class DbTest extends TestCase
{

  protected $rulesService;

  protected function setUp(): void
  {
    $model = new Rules();
    $rulesRepository = new RulesRepository($model);
    $weightValueDeliveryRepository = Mockery::mock(WeightValueDeliveryRepository::class);
    $polygonCoordinateRepository = Mockery::mock(PolygonCoordinateRepository::class);
    $polygonCoordinateItemRepository = Mockery::mock(PolygonCoordinateItemRepository::class);
    $this->rulesService = new RulesService(
      $rulesRepository,
      $weightValueDeliveryRepository,
      $polygonCoordinateRepository,
      $polygonCoordinateItemRepository
    );
    parent::setUp();
  }

  public function testGetAllRules()
  {
    $rules = $this->rulesService->getRules();
    foreach ($rules as $rule) {
      $teste = $rule['qtde_pedido_maxima_frete'];
    }
    $this->assertIsArray($rules);
  }


}