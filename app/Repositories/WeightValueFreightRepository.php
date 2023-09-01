<?php

namespace App\Repositories;

use App\Models\WeightValueFreight;

class WeightValueFreightRepository extends AbstractRepository
{

    public function __construct(WeightValueFreight $model)
    {
        $this->model = $model;
    }
}
