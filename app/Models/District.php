<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    protected $table = 'db_districts';

    protected $fillable = ['parent_id', 'name'];

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'district_id');
    }
}
