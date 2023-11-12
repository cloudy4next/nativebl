<?php

declare(strict_types=1);


namespace App\Repositories\DBSSESim;


use App\Exceptions\NotFoundException;
use App\Models\ToffeAnalytics\CampaginReport;
use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\DBSSESim\ESimRepositoryInterface;
use App\Traits\APITrait;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;
use DB;
use GuzzleHttp\Client;

class ESimRepository extends AbstractNativeRepository implements ESimRepositoryInterface
{

    use APITrait;

    // http://10.74.10.10:443/api/v1/subscriptions?filter%5Bmsisdn%5D=880190255990


    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {
        $filter = Request::get('filters')['msisdn'] ?? null;
        return $this->getESinfo((int) $filter);
    }

    public function getESinfo(int|null $msisdn)
    {
        if ($msisdn == null || $this->msisdnMakeFormate((string)$msisdn) == false) {
            session()->flash('error', 'Invalid Number Supplied!!!');

            return [];
        }


        $path = "/api/DBSS/Subscriptions?MSISDN=" . (int)$msisdn;

        $header = [
            'Authorization' => 'Bearer ' . $this->SessionCheck('access_token'),
            'client_id' => 'e828a561ac324ae1b4f7c2be757c24a9',
            'Custome-Exception' => 'Y',
        ];

        $client = new Client();
        try {
            $response = $client->request('GET', env('AUTH_SERVER') . $path, [
                'headers' => $header,
            ]);
            $decoded_payload = json_decode($response->getBody()->getContents(), false);
            $simResponseToArr = (array) $decoded_payload->data;
            $status = strpos($simResponseToArr['icc'], '74') !== false ? 'e-sim' : 'Not E-sim';
            return [
                [
                    'msisdn' => $msisdn,
                    'icc' => $simResponseToArr['icc'],
                    'status' => $status,
                    'payment-type' => $simResponseToArr['payment-type'],
                    'contract-id' => $simResponseToArr['contract-id']
                ]
            ];
        } catch (\Exception $e) {
            session()->flash('error', $this->extractErrorSummary($e->getMessage(), false));
            return [];
        }
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        $filter = $filters['msisdn'] ?? null;

        return collect($this->getESinfo((int) $filter));
    }

    public function msisdnMakeFormate($msisdn): int|bool
    {
        $msisdn = trim($msisdn);

        switch ($msisdn) {
            case strlen($msisdn) == 13 && substr($msisdn, 0, 4) == "8801":
                $msisdn = $msisdn;
                break;
            case (strlen($msisdn) == 14) && substr($msisdn, 0, 5) == "+8801":
                $msisdn = substr($msisdn, 1);
                break;
            case (strlen($msisdn) == 15) && substr($msisdn, 0, 6) == "008801":
                $msisdn = substr($msisdn, 2);
                break;
            case (strlen($msisdn) == 11) && substr($msisdn, 0, 2) == "01":
                $msisdn = "88" . $msisdn;
                break;
            case (strlen($msisdn) == 10) && substr($msisdn, 0, 1) == "1":
                $msisdn = "880" . $msisdn;
                break;
        }

        $pattern = "/8801[1,2,3,4,5,6,7,8,9][0-9]{8}/";

        return preg_match($pattern, $msisdn) ? (int) $msisdn : false;
    }
}
