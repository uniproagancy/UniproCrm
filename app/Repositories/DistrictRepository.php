<?php

namespace App\Repositories;

use App\Models\District;

class DistrictRepository extends BaseRepository
{
    public function model()
    {
        return new District();
    }
}
