<?php

namespace App\Services;

use App\Models\Rules;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueFreightRepository;

class FreightService
{
    protected $rulesRepository;

    protected $weightValueFreightRepository;

    protected $polygonCoordinateRepository;

    protected $polygonCoordinateItemRepository;

    
    public function __construct(
      RulesRepository $rulesRepository,
      WeightValueFreightRepository $weightValueFreightRepository,
      PolygonCoordinateRepository $polygonCoordinateRepository,
      PolygonCoordinateItemRepository $polygonCoordinateItemRepository
    )
    {
      $this->rulesRepository = $rulesRepository;
      $this->weightValueFreightRepository = $weightValueFreightRepository;
      $this->polygonCoordinateRepository = $polygonCoordinateRepository;
      $this->polygonCoordinateItemRepository = $polygonCoordinateItemRepository;
    }


    public function getRules()
    {
      return $this->rulesRepository->all();
    }
}

