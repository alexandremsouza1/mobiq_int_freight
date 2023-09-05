<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Base
 * @package App\Models
 * @property string $_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
abstract class BaseModel extends Model
{
    const PRIMARY_KEY       = 'id';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    /**
     * @param $data
     * @return bool
     */
    public function validate($data)
    {
        if (!$data) {
          return false;
        }

        $v = Validator::make($data, $this->rules());
        $v->validate();
        if ($v->fails()) {
          return false;
        }
        return true;
    }

}
