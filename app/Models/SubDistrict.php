<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    //
    protected $table = 'db_subdistricts';

    protected $fillable = ['parent_id', 'name'];
}
