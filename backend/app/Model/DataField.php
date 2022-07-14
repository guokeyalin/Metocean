<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DataField extends Model
{
    //
    protected $table = 'data_field';
    public $fillable = ['name', 'abbr',];
    protected $casts = ['id' => 'integer', 'name' => 'string', 'abbr' => 'string'];
   
}
