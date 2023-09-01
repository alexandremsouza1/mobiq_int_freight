<?php

namespace App\Repositories;

use App\Models\PolygonCoordinate;

class PolygonCoordinateRepository extends AbstractRepository
{

    public function __construct(PolygonCoordinate $model)
    {
        $this->model = $model;
    }
}
