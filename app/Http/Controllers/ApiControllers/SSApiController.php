<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SS\Delete;
use App\Http\Requests\Api\SS\Update;
use App\Http\Requests\Api\SS\UpdateExpired;
use App\Http\Requests\Api\SS\Upload;
use Illuminate\Support\Facades\Response;

class SSApiController extends Controller
{
    //
    public function __construct()
    {

    }

    public function uploadSSAplication(Upload $upload)
    {
        return Response::json([
            'success' => true,
        ]);
    }

    public function updateSSAplication(Update $request)
    {
        return Response::json([
            'success' => true,
        ]);
    }

    public function deleteSSAplication(Delete $request)
    {
        return Response::json([
            'success' => true,
        ]);
    }

    public function updateExpiredSSAplication(UpdateExpired $request)
    {
        return Response::json([
            'success' => true,
        ]);
    }
}
