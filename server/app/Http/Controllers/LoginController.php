<?php

namespace App\Http\Controllers;

use App\User;
use Log;

class LoginController extends Controller
{
    public function __construct(){}

    public function login()
    {
        $credentials['login_id'] = request('email');
        $credentials['password'] = request('password');
        if (! $token = auth("api")->attempt($credentials)) {
            // $error_info = $this->checkService->errorCheck(self::AUTH_CHECK);
            return response()->json($error_info);
        }
        $account = User::select('state')->where('login_id', $credentials['login_id'])->first();

        if (empty($account)) {
            // $error_info = $this->checkService->errorCheck(self::AUTH_CHECK);
            return response()->json($error_info);
        }

        if ($account['state'] == UserState::StopForAdmin) {
            // $error_info = $this->checkService->errorCheck(self::ACCOUNT_STOP_CHECK);
            return response()->json($error_info);
        } elseif ($account['state'] == UserState::StopEmailUnverified) {
            // $error_info = $this->checkService->errorCheck(self::EMAIL_UNVERIFIED_CHECK);
            return response()->json($error_info);
        } elseif ($account['state'] == UserState::Deleting) {
            // $error_info = $this->checkService->errorCheck(self::USER_DELETE);
            return response()->json($error_info);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'ログアウトしました。']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token
        ]);
    }
}

