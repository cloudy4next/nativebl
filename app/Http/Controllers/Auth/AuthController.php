<?php

/**
 * @author @cloudy4next
 * @email cloudy4next@gmail.com
 * @create date 2023-07-17 13:09:41
 * @modify date 2023-07-17 13:09:41
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Models\User;

class AuthController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
        // $this->middleware('guest')->except('logout');
    }

    public function loginViewShow()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $user = $this->loginService->UserLoginCred($request);

        if (is_string($user)) {
            return redirect()->back()->withErrors(['email' => $user]);
        }
        // set status to 1 if user logged in  to prevent using different browser at same time...
        User::where('id', $user->id)->update(['is_active' => 1, 'updated_at' => Carbon::now()]);

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    public function test(Request $request)
    {
        $accessToken = $request->session()->get('permission');
        // dd(Session::all(), $accessToken, Cookie::get('access_token'), Auth::user());
        dd($accessToken);
    }

    public function logout(Request $request)
    {
        User::where('id', Auth::user()->id)->update(['is_active' => 0]);
        Auth::guard('web')->logout();
        return redirect('/');
    }

    // public function passwordReset()
    // {
    //     return view('auth.password-reset');
    // }


}
