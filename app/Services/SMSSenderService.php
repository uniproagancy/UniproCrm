<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class SMSSenderService
{
    public static function send($phone, $text): false|string
    {
        $sender = Config::get('smsoffice.sender');
        $data = 'key='.Config::get('smsoffice.key').
            '&destination='.urlencode($phone).
            '&sender='.urlencode($sender).
            '&content='.urlencode($text);
        $url= Config::get('smsoffice.url').$data;
        return file_get_contents($url);
    }
}
