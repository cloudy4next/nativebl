<?php

namespace App\Traits;

use App\Exceptions\NotFoundException;
use GuzzleHttp\Client;
use App\Traits\HelperTrait;

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
     */
    public function apiResponse(string $method, array|null $header, array $body = null, string $path, bool $jsonDecodeFlag = false): mixed
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
            $response = $client->request($method, env('Auth_Server') . $path, [
                'headers' => $header,
                'json' => $body
            ]);
        } catch (\Exception $e) {
            throw new NotFoundException($this->extractErrorSummary($e->getMessage()));
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

    function extractErrorSummary($text): string|null
    {
        $pattern = '/"errorSummary"\s*:\s*"([^"]+)"/';

        if (preg_match($pattern, $text, $matches)) {
            $errorSummary = $matches[1];
            return $errorSummary;
        } else {
            return null;
        }
    }
}
