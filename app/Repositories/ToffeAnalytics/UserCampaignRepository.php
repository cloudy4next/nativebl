<?php declare(strict_types=1);


namespace App\Repositories\ToffeAnalytics;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use App\Models\ToffeAnalytics\CampaginReport;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ToffeAnalytics\AdsManagerTrait;

use Illuminate\Http\Request;

class UserCampaignRepository extends AbstractNativeRepository implements UserCampaignRepositoryInterface
{
    use AdsManagerTrait;

    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {
        return CampaginReport::all();
    }


    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        if ($filters == null) {
            return $query;
        }
        $filterDate = $this->makeDateTime($filters['individual_date']);
        $startDate = clone $filterDate;
        $filterDate->modify('+6 days');

        $query->whereBetween('individual_date', [$startDate, $filterDate]);

        return $query;

    }
    public function getGridQuery(): ?Builder
    {
        return CampaginReport::select();
    }


}
