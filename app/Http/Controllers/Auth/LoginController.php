<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Auth\Login;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

use App\Repositories\AdminRepository;

class LoginController extends Controller
{
    //
    public function __construct(
        private readonly AdminRepository $adminRepository,
    )
    {

    }

    public function login(): \Illuminate\Http\Response
    {
        return Response::view('template.auth.login');
    }

    public function request(Login $request): JsonResponse {
        if($request->isMethod('POST')) {
            $admin = $this->adminRepository->getItemByEmail($request->email);
            if($admin->active === 1) {
                $password = Hash::check($request->password, $admin->password);
                if($password) {
                    Auth::login($admin);
                    return Response::json([
                        'success' => true,
                        'route' => route('dashboard-index'),
                    ]);
                }
                return Response::json([
                    'success' => false,
                    'message' => 'ელ-ფოსტა ან პაროლი არასწორია!'
                ]);
            }
            return Response::json([
                'success' => false,
                'message' => 'ელ-ფოსტა ან პაროლი არასწორია!'
            ]);
        }
        return Response::json([
            'success' => false,
        ]);
    }
}
