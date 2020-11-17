<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// 追加
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    private $authManager;　// 追加

    public function __construct(AuthManager $authManager)　// 追加
    {
        $this->authManager = $authManager;　 //　追加
        $this->middleware('guest')->except('logout');
    }

    //　追加
    public function login(Request $request): JsonResponse
    {
        // $guard = $this->authManager->guard('api');
        // $token = $guard->attempt([
        //     'email' =>  $request->get('email'),
        //     'password'  =>  $request->get('password'),
        // ]);
        // if (!$token) {
        //     return new JsonResponse(__('auth.failed'));
        // }
        // return new JsonResponse($token);


        $credentials = request(['login_id', 'password']);
        if (!$token = auth("shops")->attempt($credentials)) {
            $error_info = $this->checkService->errorCheck(self::AUTH_CHECK);
            return response()->json($error_info);
        }
        $shop_info = Shop::select('*')->where('login_id', $credentials['login_id'])->first()->toArray();
        return response()->json(['shop_info' => $shop_info, 'access_token' => $token]);


    }
}
