<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        return Response::view('template.dashboard.index', [

        ]);
    }
}
