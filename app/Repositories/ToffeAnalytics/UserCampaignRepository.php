<?php declare(strict_types=1);


namespace App\Repositories\ToffeAnalytics;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use App\Models\ToffeAnalytics\CampaginReport;
use DB;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ToffeAnalytics\AdsManagerTrait;
use Illuminate\Support\Facades\Request;


class UserCampaignRepository extends AbstractNativeRepository implements UserCampaignRepositoryInterface
{
    use AdsManagerTrait;

    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {
        $id = Request::get('lineitem');
        return CampaginReport::where('campaign_id', $id)->get();

    }


    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        if ($filters == null) {
            return $query;
        }
        $filterDate = explode(" - ", $filters['date_range']);
        return CampaginReport::where('campaign_id', $filters['lineitem'])->whereBetween('individual_date', [$filterDate[0], $filterDate[1]]);

    }
    public function getGridQuery(): ?Builder
    {
        $id = Request::get('lineitem');
        return CampaginReport::where('campaign_id', $id)->select();
    }


}
