<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        return Response::view('template.dashboard', [

        ]);
    }
}
