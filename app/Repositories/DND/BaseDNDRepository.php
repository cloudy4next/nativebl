<?php

declare(strict_types=1);


namespace App\Repositories\DND;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\DND\BaseDNDRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\DND\DNDChannel;
use App\Models\DND\DNDHistory;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Traits\APITrait;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;

class BaseDNDRepository extends AbstractNativeRepository implements BaseDNDRepositoryInterface, CrudGridLoaderInterface
{
    use APITrait;

    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {
        return collect([]);
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        if (!isset($filters['msisdn'])) {
            return collect([]);
        }
        if (!$this->msisdnMakeFormate($filters['msisdn'])) {
            session()->flash('error', 'Invalid Number Supplied!!!');
            return collect([]);
        }
        $msisdn = $this->msisdnMakeFormate($filters['msisdn']);

        $this->apiStatus($msisdn, $filters['channel_name']);

        if ($this->isOnnetMsisdn($msisdn)) {
            $history = DNDHistory::select('dnd_history.*', 'dnd_channels.channel_name')
                ->where('dnd_history.msisdn', $msisdn)
                ->whereDate('dnd_history.created_at', '>=', now()->subDays(30))
                ->join('dnd_channels', 'dnd_history.dnd_channel_id', '=', 'dnd_channels.id')
                ->get();
            return collect($history);
        }
        return collect([]);
    }

    public function sendDNDData(array $dndData)
    {
        $status = ($dndData['inlineRadioOptions'] == 'on') ? 'optin' : 'optout';
        $json = [
            "msisdn" =>  $dndData['msisdn'],
            "requestType" => $status,
            "dndChannelName" => $dndData['channel'],
            "smsFlag" => false,
            "agentId" => Auth::user()->id,
        ];
        $path = '/Api/DnDManagement/digital';
        $this->apiResponse('POST', null, $json, $path);
        return $this->apiResponse('POST', null, $json, $path)->message;
    }

    public function channels(): array
    {
        $channel = DNDChannel::pluck('channel_name')->toArray();
        return $channel = array_values(array_diff($channel, ['INVALID']));
    }

    public function apiStatus(int $msisdn, ?string $channel): void
    {
        $path = '/Api/OpenAPI/check/status?msisdn=' . $msisdn . '&dndChannelName=' .  $channel;

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
            session()->flash('success', $response->message);
        } catch (\Exception $e) {
            session()->flash('error', $this->extractErrorSummary($e->getMessage(), false));
        }
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



    public function isOnnetMsisdn(int $msisdn): bool
    {
        $url = "http://10.10.31.54:8080/HttpReceiver/HttpReceiver2?reply=true&destinationType=queue&destinationName=mnp&traceON=true&msisdn=" . $msisdn . "&target=MNPMDB";
        $result =  file_get_contents($url);

        return $result == "5000" ? true : false;
    }
}
