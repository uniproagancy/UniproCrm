<?php

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository extends BaseRepository
{

    public function model() {
        return new Admin();
    }

}
