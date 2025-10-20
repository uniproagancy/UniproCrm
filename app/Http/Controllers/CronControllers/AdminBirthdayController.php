<?php

namespace App\Http\Controllers\CronControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\SMSSenderService;
use Illuminate\Http\Request;

use Carbon\Carbon;

class AdminBirthdayController extends Controller
{
    //
    public function index()
    {
        $admins = Admin::whereDate('b_date', Carbon::now())->where('active', 1)->get();
        if(count($admins) > 0) {
            foreach ($admins as $admin) {
                SMSSenderService::send($admin->phone, $admin->name.' ჩვენი გუნდის სახელით გილოცავთ დაბადების დღეს!');
            }
        }
    }
}
