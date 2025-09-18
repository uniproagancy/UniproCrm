<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    //
    protected $table = 'db_streets';

    protected $fillable = ['ss_id','parent_id','ss_parent_id', 'name'];
}
