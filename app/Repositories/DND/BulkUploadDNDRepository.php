<?php

declare(strict_types=1);


namespace App\Repositories\DND;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\DND\BulkUploadDNDRepositoryInterface;
use App\Models\DND\DNDChannel;
use Illuminate\Support\Collection;
use App\Traits\APITrait;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;

class BulkUploadDNDRepository extends AbstractNativeRepository implements BulkUploadDNDRepositoryInterface, CrudGridLoaderInterface
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
        return collect($this->apiStatus((int)$filters['msisdn'], $filters['channel_name']));
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
        return true;
    }

    public function channels(): array
    {
        $channel = DNDChannel::pluck('channel_name')->toArray();
        return $channel = array_values(array_diff($channel, ['INVALID']));
    }

    public function getExportData()
    {

    }
}
