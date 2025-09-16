<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ss_service';
    }
}
