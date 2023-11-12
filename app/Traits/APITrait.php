<?php

namespace App\Traits;

use App\Exceptions\NotFoundException;
use GuzzleHttp\Client;
use App\Traits\HelperTrait;
use Auth;

trait APITrait
{
    use HelperTrait;


    /**
     * @param string $method GET..POST..etc..
     * @param array|null $header Can Use Custom Header
     * @param array $body json type
     * @param string $path eg: /API/AppManagement or /{}/AppManagement
     * @param bool $jsonDeacodeFlag bool
     * @return mixed
     * @throws NotFoundException
     */
    public function apiResponse(string $method, array|null $header, array $body = null, string $path, bool $jsonDecodeFlag = false, bool $secondLevelError = false): mixed
    {
        if ($header == null) {

            $header = [
                'Authorization' => 'Bearer ' . $this->SessionCheck('access_token'),
                'client_id' => 'e828a561ac324ae1b4f7c2be757c24a9',
                'Custome-Exception' => 'Y',
            ];
        }
        $client = new Client();
        try {
            $response = $client->request($method, env('AUTH_SERVER') . $path, [
                'headers' => $header,
                'json' => $body
            ]);
        } catch (\Exception $e) {

            throw new NotFoundException($this->extractErrorSummary($e->getMessage(), $secondLevelError));
        }

        $res = $response->getBody()->getContents();
        $decoded_payload = json_decode($res, $jsonDecodeFlag);
        return $decoded_payload;
    }


    public function getUserApplicationsByID(): array
    {
        $path = '/Api/AppUserManagement/Aplications?id=' . (int) $this->SessionCheck('applicationID');
        $response = $this->apiResponse('GET', null, null, $path);
        return $this->getMultiValueFromArr($response->data);
    }

    public function userPermission(): array
    {
        $path = '/Api/AppUserManagement/AppUserPermissions?id=' . Auth::user()->id;
        $response = $this->apiResponse('GET', null, null, $path, true);
        return $this->setOnlyArray($this->featureSet($this->GetFeatSets($response['data'], null)));
    }

    private function getUserInformation(string $accessKey = null): array|object
    {
        $path = '/Api/AppUserManagement/AppUser?id=' . Auth::user()->id . '&email=null';
        $response = $this->apiResponse('GET', null, null, $path, false);
        switch ($response) {
            case $accessKey == 'menu':
                return $response->data->menus;
            case $accessKey == 'permissions':
                return $response->data->permissions;
            default:
                return $response->data;
        }
    }

    function extractErrorSummary($text, bool $secondLevelError): string|null
    {
        $pattern = ($secondLevelError == true) ? '/"ErrorDescription"\s*:\s*\["([^"]+)"\]/' : '/"errorSummary"\s*:\s*"([^"]+)"/';
        if (preg_match($pattern, $text, $matches)) {
            $errorSummary = $matches[1];
            return $errorSummary;
        } else {
            return null;
        }
    }
}
