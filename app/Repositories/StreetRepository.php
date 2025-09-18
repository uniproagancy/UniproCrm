<?php

namespace App\Repositories;

use App\Models\Street;

class StreetRepository extends BaseRepository
{
    public function model()
    {
        return new Street();
    }
}
