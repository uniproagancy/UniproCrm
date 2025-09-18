<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    //
    protected $table = "db_municipalities";

    protected $fillable = ['ss_id', 'name'];
}
