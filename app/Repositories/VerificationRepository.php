<?php

namespace App\Repositories;

use App\Models\Verification;

class VerificationRepository extends BaseRepository
{
    public function model()
    {
        return new Verification();
    }
}
