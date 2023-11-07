<?php

declare(strict_types=1);
/**
 * @author @cloudy4next
 * @email cloudy4next@gmail.com
 * @create date 2023-07-17 13:12:04
 * @modify date 2023-07-17 13:12:04
 * @desc [description]
 */

namespace App\Services\Auth;

use App\Models\User;
use GuzzleHttp\Client;
use Cookie;
use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\sha512;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use App\Traits\HelperTrait;
use App\Traits\APITrait;
use App\Exceptions\NotFoundException;

class LoginService
{
    use HelperTrait, APITrait;

    /**
     * UserLoginCred
     * return and redirect user to home route.
     */
    public function UserLoginCred($request): User
    {
        $checkUser = $this->CheckIfUserExits($request->email);

        if ($checkUser === false) {
            $tokenUser = $this->TokenGenNSetCookie($request->email, $request->password, $request);

            $this->CreateUser($tokenUser[0], $tokenUser[1], $tokenUser[2]);
            return $this->CheckIfUserExits($request->email);
        }
        // Application@@2023

        $this->TokenGenNSetCookie($request->email, $request->password, $request);
        return $checkUser;
    }

    private function TokenGenNSetCookie(string $email, string $password, $request)
    {
        $payload = $this->AccessTokenRequest($email, $password);

        $userObject = $this->GetUserPayload($payload, $this->GetUserIdFToken($payload, 'userID'), $this->GetUserIdFToken($payload, 'userEmail'));
        $this->CookieSessTokenSet($userObject, $request);
        return $userObject;
    }

    private function CookieSessTokenSet(array $tokenWRole, mixed $request): void
    {

        $accessTokenExpiresAt = now()->addMinutes(60);
        $request->session()->put('access_token', $tokenWRole[6]);
        $request->session()->put('access_token_expires_at', $accessTokenExpiresAt);
        $request->session()->put('refresh_token', $tokenWRole[7]);
        $request->session()->put('role', $tokenWRole[4]);
        $request->session()->put('menus', $tokenWRole[3]);
        $request->session()->put('applicationID', $tokenWRole[8]);
        $request->session()->put('profilePicID', $tokenWRole[5]);

        Cookie::queue('access_token', $tokenWRole[6]);
        Cookie::queue('refresh_token', $tokenWRole[7]);
    }

    private function AccessTokenRequest(string $email, string $password)
    {
        $apiUser = 'e828a561ac324ae1b4f7c2be757c24a9';
        $apiPassword = 'd8bcfe752d2844afa0f9ad0c0dcbc483';
        $baseEncode = base64_encode($apiUser . ':' . $apiPassword);
        $grantTypes = ['email_password']; // 'bl_active_directory'

        $client = new Client();

        $allFailed = true;
        foreach ($grantTypes as $grantType) {
            $headers = [
                'grant_type' => $grantType,
                'Scope' => 'APIGW3 IDM3',
                'client_id' => $apiUser,
                'Custome-Exception' => 'Y',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $baseEncode,
            ];
            $json = [
                'email' => $email,
                'password' => $password,
                'grant_type' => $grantType,
                'scope' => 'APIGW3 IDM3'
            ];

            try {
                $response = $client->request('POST', env('AUTH_SERVER') . '/api/auth/token', [
                    'headers' => $headers,
                    'json' => $json
                ]);
                $res = $response->getBody()->getContents();
                $decodedPayloads = json_decode($res);

                $allFailed = false;

                return $decodedPayloads;
            } catch (\Exception $e) {
            }
        }

        throw new NotFoundException('User Not Found');
    }

    // need to verifiy token
    private function ParseTokenWVal(object $payload, string $secretKey)
    {
        $accessToken = $payload->access_token;
        $refreshToken = $payload->refresh_token;
        $key = InMemory::plainText($secretKey);
        try {
            $token = (new JwtFacade())->parse(
                $accessToken,
                new Constraint\SignedWith(new sha512(), $key),
                new Constraint\StrictValidAt(
                    new FrozenClock(new DateTimeImmutable('now'))
                )
            );
        } catch (\Exception $e) {
            throw new NotFoundException('Invaild Token.');
        }
        return $token->claims();
    }

    private function GetUserIdFToken(object $payload, string $Item): string
    {
        $accessToken = $payload->access_token;
        $parser = new Parser(new JoseEncoder());
        $token = $parser->parse($accessToken);

        assert($token instanceof UnencryptedToken);

        return $token->claims()->get($Item);
    }

    private function GetUserPayload(object $accessToken, string $id, string $UserEmail): mixed
    {
        $token = $accessToken->access_token;
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'client_id' => 'e828a561ac324ae1b4f7c2be757c24a9',
            'Custome-Exception' => 'Y'
        ];

        $path = '/Api/AppUserManagement/AppUser?id=' . $id . '&email=' . $UserEmail;
        $response = $this->apiResponse('GET', $headers, null, $path, true);
        if ($response['data']['isUserActive'] == 0) {
            throw new NotFoundException('User Not Found');
        }

        return $this->UserDataProcessing($response, $accessToken->access_token, $accessToken->refresh_token);
    }

    private function CreateUser(string $id, string $name, string $email): void
    {
        $user = new User();
        $user->id = $id;
        $user->user_name = $name;
        $user->email = $email;
        $user->save();
    }

    private function CheckIfUserExits(string $email): User|bool
    {
        $user = User::where('email', '=', $email)->first();
        if ($user === null) {
            return false;
        }
        return $user;
    }
    public function passwordReset(?array $json)
    {
        $apiUser = 'e828a561ac324ae1b4f7c2be757c24a9';
        $apiPassword = 'd8bcfe752d2844afa0f9ad0c0dcbc483';
        $baseEncode = base64_encode($apiUser . ':' . $apiPassword);
        $headers = [
            'Scope' => 'APIGW3 IDM3',
            'client_id' => $apiUser,
            'Custome-Exception' => 'Y',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $baseEncode,
        ];
        $path = '/api/DBSS/PasswordReset';
        try {
            $this->apiResponse('POST', null, $json, $path, false, true);
        } catch (\Exception $e) {
            throw new NotFoundException("Email or Token is invlid");
        }
    }
}
