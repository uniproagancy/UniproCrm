<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Config;

    class SSService
    {

        // SS API - GET TOKEN
        public static function getToken(): array
        {
            $token = Http::asForm()->post(Config::get('ss.token_url'), [
                'username' => Config::get('ss.username'),
                'password' => Config::get('ss.password'),
                'client_id' => Config::get('ss.client_id'),
                'client_secret' => Config::get('ss.client_secret'),
                'scope' => Config::get('ss.scope'),
                'grant_type' => 'password',
            ]);
            if ($token->failed()) {
                return [
                    'success' => false,
                    'error' => $token->body(),
                ];
            }
            return [
                'success' => true,
                'access_token' => $token['access_token'],
            ];
        }
    }
