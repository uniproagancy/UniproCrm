<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    //
    protected $table = 'db_agents';

    protected $casts = [
        'cities' => 'array',
        'districts' => 'array',
        'subdistricts' => 'array',
    ];

}
