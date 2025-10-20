<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\SS\Delete;
use App\Http\Requests\Api\SS\Update;
use App\Http\Requests\Api\SS\UpdateExpired;
use App\Http\Requests\Api\SS\Upload;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

use App\Services\SSService;

class SSApiController extends Controller
{
    //
    public function __construct()
    {

    }
    public function uploadSSAplication(Upload $request)
    {
        $application_data = [
            'realEstateTypeId' => $request->realEstateTypeId,
            'realEstateDealTypeId' => $request->realEstateDealTypeId,
            'cityId' => $request->cityId,
            'streetId' => $request->streetId,
            'subdistrictId' => $request->subdistrictId,
            'descriptionGe' => $request->descriptionGe,
            'descriptionEn' => $request->descriptionEn,
            'descriptionRu' => $request->descriptionRu,
            'cadastralCode' => $request->cadastralCode,
            'price' => $request->price,
            'showSiteCurrencyId' => $request->showSiteCurrencyId,
            'currencyId' => $request->currencyId,
            'locationLatitude' => $request->locationLatitude,
            'locationLongitude' => $request->locationLongitude,
            'priceType' => 1,
            'phoneNumbers' => [
                'phoneNumber' => '598134123',
                'isMain' => true,
                'isDelete' => false,
            ],
            'agentUserId' => '6a843c30-35a7-4692-b581-d51b2d7bf8a3',
        ];
        if(!empty($request->parameters)) {
            foreach ($request->parameters as $key => $value) {
                if(empty($value)) {
                    $application_data[$key] = false;
                } else {
                    $application_data[$key] = true;
                }
            }
        }
        switch ($application_data['realEstateTypeId']) {
            case 5:
                $application_data['rooms'] = $request->rooms;
                $application_data['areaOfHouse'] = $request->areaOfHouse;
                $application_data['bedrooms'] = $request->bedrooms;
                $application_data['totalArea'] = $request->totalArea;
                $application_data['floor'] = $request->floor;
                $application_data['floors'] = $request->floors;
                $application_data['project'] = $request->project;
                $application_data['state'] = $request->state;
            break;
            case 3:
                $application_data['totalArea'] = $request->totalArea;
            break;
            case 4:
            case 1:
                $application_data['rooms'] = $request->rooms;
                $application_data['areaOfHouse'] = $request->areaOfHouse;
                $application_data['areaOfYard'] = $request->areaOfYard;
                $application_data['state'] = $request->state;
            break;
            case 6:
                $application_data['commercialRealEstateType'] = $request->commercialRealEstateType;
                $application_data['state'] = $request->state;
            break;
        }
        $uploadApplication = SSService::uploadSSAplication($application_data);
        return Response::json([
            'success' => true,
        ]);
    }









    public function updateSSAplication(Update $request)
    {
        $updateApplication = SSService::updateApplication([]);
        return Response::json([
            'success' => true,
        ]);
    }

    public function deleteSSAplication(Delete $request)
    {
        $deleteApplication = SSService::deleteApplication([]);
        return Response::json([
            'success' => true,
        ]);
    }

    public function updateExpiredSSAplication(UpdateExpired $request)
    {
        $updateExpiredApplication = SSService::updateExpiredApplication([]);
        return Response::json([
            'success' => true,
        ]);
    }
}
