<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use App\Contracts\Services\Settings\UserServiceInterface;

/**
 * @author cloudy4nexts <jahangir7200@live.com>
 */
class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __construct(private UserServiceInterface $userServiceInterface)
    {
        $this->userServiceInterface = $userServiceInterface;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $passwordChangeRouteName = 'App\Http\Controllers\Settings\UserController@passwordChange';
        if (
            Auth::check() && $this->CheckIsMustPassword() &&
            $request->route()->getActionName() !== $passwordChangeRouteName
        ) {
            return redirect('profile/password/change');
        }
        return $next($request);

    }

    public function CheckIsMustPassword(): bool
    {
        $response = $this->userServiceInterface->getSingleUser(Auth::user()->id);

        return ($response->data->isMustChangePassword == 1) ? true : false;
    }

}
