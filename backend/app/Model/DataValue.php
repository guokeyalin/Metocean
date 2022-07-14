<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataValue extends Model
{
    //
    protected $table = 'data_value';
    public $fillable = ['value', 'field_id', 'date_time',];
    protected $casts = ['id' => 'integer', 'field_id' => 'integer', 'value' => 'float', 'date_time' => 'date'];
   
}
