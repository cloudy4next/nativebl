<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CheckTokenExpiration
{
    public function handle($request, Closure $next)
    {

        return $next($request);

        // if (Session::has('access_token') && Session::has('access_token_expires_at')) {
        //     $accessTokenExpiresAt = Session::get('access_token_expires_at');
        //     $refreshToken = Session::get('refresh_token');
        //     if (now()->gt($accessTokenExpiresAt)) {
        //         $newAccessToken = $this->refreshAccessToken($refreshToken);
        //         if ($newAccessToken) {
        //             session(['access_token' => $newAccessToken, 'access_token_expires_at' => now()->addMinutes(60)]);
        //         } else {
        //             auth()->logout();
        //             return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        //         }
        //     }
        // }

        // return $next($request);
    }
    private function refreshAccessToken(string $refreshToken): mixed
    {
        $client = new Client();
        try {

            $response = $client->post('http://10.10.31.123:8090/api/auth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => 'your-client-id',
                    'client_secret' => 'your-client-secret',
                    'scope' => '',
                ],
            ]);

        } catch (\Exception $e) {
            return null;
        }
        $data = json_decode($response->getBody(), true);
        return $data['access_token'];

    }

}
