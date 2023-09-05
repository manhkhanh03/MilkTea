<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) {
        $login_name = User::where('login_name', $request->login_name)->first();
        if($login_name) {
            $credentials = $request->only('login_name', 'password');
           if (Auth::attempt($credentials)) {
               return response()->json(['Message' => 'Success'], 200);
           } else {
               return response()->json(['status' => 'password'], 401, ['Unauthorized']);
           }
        }else {
            return response()->json(['status' => 'username'], 401, ['Unauthorized']);
        }
    }

    public function logout() {
        Auth::logout();
        return response()->json(['status' => 'Success'], 200);
    }
}
