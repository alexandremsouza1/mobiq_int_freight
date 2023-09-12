<?php

namespace App\Services;

use App\Models\Rules;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueDeliveryRepository;

class RulesService
{
    protected $rulesRepository;

    protected $weightValueDeliveryRepository;

    protected $polygonCoordinateRepository;

    protected $polygonCoordinateItemRepository;

    
    public function __construct(
      RulesRepository $rulesRepository,
      WeightValueDeliveryRepository $weightValueDeliveryRepository,
      PolygonCoordinateRepository $polygonCoordinateRepository,
      PolygonCoordinateItemRepository $polygonCoordinateItemRepository
    )
    {
      $this->rulesRepository = $rulesRepository;
      $this->weightValueDeliveryRepository = $weightValueDeliveryRepository;
      $this->polygonCoordinateRepository = $polygonCoordinateRepository;
      $this->polygonCoordinateItemRepository = $polygonCoordinateItemRepository;
    }


    public function getRules()
    {
      return $this->rulesRepository->findAllByKey('status', 1);
    }
}

