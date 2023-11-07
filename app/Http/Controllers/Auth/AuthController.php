<?php

/**
 * @author @cloudy4next
 * @email cloudy4next@gmail.com
 * @create date 2023-07-17 13:09:41
 * @modify date 2023-07-17 13:09:41
 */

namespace App\Http\Controllers\Auth;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToffeAnalytics\ToffeeLoginRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Auth\LoginService;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Auth;
use Session;

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

    public function toffeeLoginViewShow()
    {
        return view('auth.toffee-login');
    }

    public function login(ToffeeLoginRequest $request)
    {
        if ($request->password == null) {
            throw new NotFoundException("Please Enter the password");
        }
        $user = $this->loginService->UserLoginCred($request);

        // set status to 1 if user logged in to prevent using different browser at the same time...
        User::where('id', $user->id)->update(['is_active' => 1, 'updated_at' => Carbon::now()]);
        Auth::login($user);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function test(Request $request)
    {
        $accessToken = $request->session()->get('menus');
        dd(Session::all(), $accessToken);
        // dd($accessToken);
    }

    public function logout(Request $request)
    {
        User::where('id', Auth::user()->id)->update(['is_active' => 0]);
        Auth::guard('web')->logout();
        return redirect('/login');
    }

    public function passwordReset($token)
    {
        return view('auth.password-reset', ['token' => $token]);
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        $json = [
            "emailAddress" => $request->email,
            "password" => bcrypt($request->get('password')),
            'resetToken' => $request->token,
        ];

        $this->loginService->passwordReset($json);

        return redirect()->route('login')->with('success', 'Password reset successful. You can now log in with your new password.');
    }
}
