<?php

namespace App\Repositories;

use App\Models\WeightValueDelivery;

class WeightValueDeliveryRepository extends AbstractRepository
{

    public function __construct(WeightValueDelivery $model)
    {
        $this->model = $model;
    }
}
