<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Config;

    class SSService
    {
        public function getToken(): array
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
            $data = $token->json();
            return [
                'success' => true,
                'access_token' => $data['access_token'] ?? null,
                'expires_in' => $data['expires_in'] ?? null,
            ];
        }

        public function uploadImage($image_data)
        {
            $image = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.upload_image_url'), $image_data
            );
        }

        public function uploadApplication($application_data)
        {
            $application = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.upload_application_url'),
                    $application_data
            );
        }

        public function updateApplication($application_data)
        {
            $application = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.update_application_url'),
                    $application_data
                );
        }

        public function deleteApplication($application_id)
        {
            $application_delete = Http::withToken($this->getToken()['access_token'])->delete(Config::get('ss.delete_url'),
                [
                    'applicationId' => $application_id,
                ]
            );
            if ($application_delete->failed()) {
                return [
                    'success' => false,
                    'error' => $application_delete->body(),
                ];
            }
            return [
                'success' => true,
            ];
        }

        public function expiredApplicationUpdate($application_id)
        {
            $expired_application = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.expired_application_url'),
                [
                    'applicationId' => $application_id,
                ]
            );
            if ($expired_application->failed()) {
                return [
                    'success' => false,
                    'error' => $expired_application->body(),
                ];
            }
            return [
                'success' => true,
            ];
        }

    }
