<?php

namespace App\Repositories;

use App\Models\Rules;

class RulesRepository extends AbstractRepository
{

    public function __construct(Rules $model)
    {
        $this->model = $model;
    }
}
