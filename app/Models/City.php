<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'db_cities';

    protected $fillable = ['ss_id','parent_id','ss_parent_id', 'name'];
}
