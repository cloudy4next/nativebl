<?php declare(strict_types=1);


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
        if ($msisdn == null) {
            return [];
        }
        if (strlen((string) $msisdn) <> 10) {
            throw new NotFoundException('MSISDN Must be 10 Digits.');
        }
        $path = "/api/DBSS/Subscriptions?MSISDN=" . $msisdn;
        $simResponse = $this->apiResponse('GET', null, null, $path, false, true);
        $simResponseToArr = (array) $simResponse->data;
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
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        $filter = $filters['msisdn'] ?? null;

        return collect($this->getESinfo((int) $filter));
    }

}
