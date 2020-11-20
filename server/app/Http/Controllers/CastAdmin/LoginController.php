<?php
namespace App\Http\Controllers\CastAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use Log;

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
    protected $redirectTo = '/cast_admin/home'; // 変更
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:cast_admin')->except('logout'); //変更
    }
    
    public function showLoginForm()
    {
        return view('cast_admin.login');  //変更
    }
 
    protected function guard()
    {
        return Auth::guard('cast_admin');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('cast_admin')->logout();  //変更
        $request->session()->flush();
        $request->session()->regenerate();
 
        return redirect('/cast_admin/login');  //変更
    }
}