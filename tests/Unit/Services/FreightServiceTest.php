<?php

namespace Tests\Unit\Services;

use App\Integrations\Client;
use App\Integrations\Source;
use App\Models\PolygonCoordinate;
use App\Models\PolygonCoordinateItem;
use App\Models\Rules;
use App\Models\WeightValueFreight;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueFreightRepository;
use App\Services\FreightService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;


class FreightServiceTest extends TestCase
{

    protected $service;



    protected function setUp(): void
    {
      parent::setUp();
      $client = new Client();
      $source = new Source($client);
      $rulesRepository = new RulesRepository(new Rules);
      $weightValueFreightRepository = new WeightValueFreightRepository(new WeightValueFreight());
      $polygonCoordinateRepository = new PolygonCoordinateRepository(new PolygonCoordinate());
      $polygonCoordinateItemRepository = new PolygonCoordinateItemRepository(new PolygonCoordinateItem());
      $this->service = new FreightService($source,$rulesRepository,$weightValueFreightRepository,$polygonCoordinateRepository,$polygonCoordinateItemRepository);
    }


    public function testGetFreight()
    {
      $data = $this->service->getFreight('0068000249',0);
      $this->assertIsArray($data);
    }
    
    
}
