<?php

namespace App\Repositories;

use App\Models\Municipality;

class MunicipalityRepository extends BaseRepository
{
    public function model()
    {
        return new Municipality();
    }
}
