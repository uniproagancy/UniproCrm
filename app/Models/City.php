<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'db_cities';

    protected $fillable = ['ss_id','city_id', 'name'];

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }
}
