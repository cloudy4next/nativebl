<?php

declare(strict_types=1);


namespace App\Repositories\DND;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\DND\BaseDNDRepositoryInterface;
use App\Models\DND\BaseDND;
use App\Models\DND\DNDChannel;
use Illuminate\Support\Collection;
use App\Traits\APITrait;
use Exception;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Redirect;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Symfony\Component\HttpFoundation\Request;

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
        if (isset($filters['msisdn']) == null) {
            return collect([]);
        }
        $data = $this->apiStatus((int)$filters['msisdn'], $filters['channel_name']);

        if (is_array($data)) {
            return collect($data);
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
            "smsFlag" => false
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

    public function apiStatus(int $msisdn, ?string $channel)
    {
        $path = '/Api/OpenAPI/check/status?msisdn=' . $msisdn . '&dndChannelName=' .  $channel;
        try {
            return $this->apiResponse('GET', null, null, $path)->data;
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
