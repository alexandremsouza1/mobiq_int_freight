<?php

namespace App\Repositories;

use App\Models\PolygonCoordinateItem;

class PolygonCoordinateItemRepository extends AbstractRepository
{

    public function __construct(PolygonCoordinateItem $model)
    {
        $this->model = $model;
    }
}
