<?php

namespace Tests\Unit\Services;

use App\Factory\FactoryRulesItem;
use App\Integrations\Source;
use App\Services\CoordinatesService;
use App\Services\RulesItemService;
use App\Services\WeightValueDeliveryService;
use Carbon\Carbon;
use Mockery;
use Tests\TestCase;


class RulesItemServiceTest extends TestCase
{

    protected $service1;

    protected $service2;



    protected function setUp(): void
    {
      $source1 = Mockery::mock(Source::class);
      $holydays = file_get_contents(__DIR__ . '/input/consultar_feriado.json');
      $source1->shouldReceive('getConsultarFeriados')->andReturn(json_decode($holydays,true));


      $this->service1 = $this->mockRulesItemService($source1);

      $source2 = new Source();
      $this->service2 = $this->mockRulesItemService($source2);
      parent::setUp();
    }

    public function mockRulesItemService($source)
    {
      $factoryRulesItem = Mockery::mock(FactoryRulesItem::class);
      $weightValueDeliveryService = Mockery::mock(WeightValueDeliveryService::class);
      $coordinatesService = Mockery::mock(CoordinatesService::class);
      $rulesItemService = new RulesItemService(
        $source,
        $factoryRulesItem,
        $weightValueDeliveryService,
        $coordinatesService
      );
      return $rulesItemService;
    }



    public function testCalculateBusinessDayWithMock()
    {
      $initialDate = Carbon::create(2023, 9, 5, 0, 0, 0, 'America/Sao_Paulo');
      $deliveryDate = $this->service1->calculateBusinessDay(2, $initialDate);
      $this->assertEquals('2023-09-08 00:00:00', $deliveryDate);
    }

    public function testCalculateBusinessDayWithOutMock()
    {
      $initialDate = Carbon::create(2023, 9, 8, 0, 0, 0, 'America/Sao_Paulo');
      $deliveryDate = $this->service2->calculateBusinessDay(2, $initialDate);
      $this->assertEquals('2023-09-11 00:00:00', $deliveryDate);
    }


}