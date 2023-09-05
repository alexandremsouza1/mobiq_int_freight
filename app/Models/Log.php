<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends BaseModel
{

    use SoftDeletes;

    protected $table = 'log';
    /**
    * Timestamp field
    *
    * @var array
    */
    protected $dates =  [
        'created_at', 'updated_at','deleted_at'
    ];


    protected $fillable = [
        'name',
        'method' ,
        'url',
        'status_code',
        'user_agent',
        'request_ip',
        'response_content'
    ];
    /**
     * Basic rule of database
     *
     * @var array
     */
    public function rules()
    {
        return [];
    }


}