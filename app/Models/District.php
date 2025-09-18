<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $table = 'db_districts';

    protected $fillable = ['ss_id','parent_id','ss_parent_id', 'name'];
}
