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

        public function getMunicipalities()
        {
            $municipalities = Http::withToken($this->getToken()['access_token'])
                    ->post(Config::get('ss.get_municipalities_url'));
            if ($municipalities->failed()) {
                return [
                    'success' => false,
                    'error' => $municipalities->body(),
                ];
            }
            return [
                'success' => true,
                'municipalities' => $municipalities->json(),
            ];
        }

        public function getCities()
        {
            $cities = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.get_cities_url'));
            if ($cities->failed()) {
                return [
                    'success' => false,
                    'error' => $cities->body(),
                ];
            }
            return [
                'success' => true,
                'cities' => $cities->json(),
            ];
        }

        public function getDistricts($city_id)
        {
            $districts = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.get_districts_url'), [
                    'cityId' => $city_id
                ]);
            if ($districts->failed()) {
                return [
                    'success' => false,
                    'error' => $districts->body(),
                ];
            }
            return [
                'success' => true,
                'districts' => $districts->json(),
            ];
        }

        public function getSubDistricts($district_id)
        {
            $sub_districts = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.get_subdistricts_url'), [
                    'districtId' => $district_id
                ]);
            if ($sub_districts->failed()) {
                return [
                    'success' => false,
                    'error' => $sub_districts->body(),
                ];
            }
            return [
                'success' => true,
                'sub_districts' => $sub_districts->json(),
            ];
        }

        public function getStreets($subdistrict_id)
        {
            $streets = Http::withToken($this->getToken()['access_token'])
                ->post(Config::get('ss.get_streets_url'), [
                    'subDistrictId' => $subdistrict_id
                ]);
            if ($streets->failed()) {
                return [
                    'success' => false,
                    'error' => $streets->body(),
                ];
            }
            return [
                'success' => true,
                'streets' => $streets->json(),
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

        public function updateExpiredApplication($application_id)
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
