<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SMSSender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms_sender_service';
    }
}
