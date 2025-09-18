<?php

namespace App\Repositories;

use App\Models\SubDistrict;

class SubDistrictRepository extends BaseRepository
{
    public function model()
    {
        return new SubDistrict();
    }
}
