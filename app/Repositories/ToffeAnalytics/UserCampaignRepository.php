<?php declare(strict_types=1);

namespace App\Repositories\ToffeAnalytics;

use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use App\Models\ToffeAnalytics\CampaginReport;
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

    public function getExportData($campaignId, $startDate, $endDate): bool|array
    {
        $allReports = CampaginReport::whereCampaignId($campaignId)
            ->whereBetween('individual_date', [$startDate, $endDate])
            ->get();
        $formattedData = [];
        foreach ($allReports as $report) {
            $formattedData[] = [
                'individual_date' => $report->individual_date,
                'impression' => $report->impression,
                'clicks' => $report->clicks,
                'complete_views' => $report->complete_views,
                'active_viewable_impression' => $report->active_viewable_impression,
                'viewable_impression' => number_format((float)$report->viewable_impression, 2),
                'ctr' => number_format((float)$report->ctr, 2),
                'completion_rate' => number_format((float)$report->completion_rate, 2),
            ];
        }

        return $formattedData;
    }

    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        if ($filters == null) {
            return $query;
        }
        $filterDate = explode(" - ", $filters['individual_date']);
        return CampaginReport::where('campaign_id', $filters['lineitem'])->whereBetween('individual_date', [$filterDate[0], $filterDate[1]]);

    }

    public function getGridQuery(): ?Builder
    {
        $id = Request::get('lineitem');
        return CampaginReport::where('campaign_id', $id)->select();
    }

    public function getHalfMonthData($data)
    {
        $id = $data['id'];
        $startDate = $data['startDate'] ?? null;
        $endDate = $data['endDate'] ?? null;

        $chartDataset = CampaginReport::where('campaign_id', $id);

        if ($startDate != null && $endDate != null) {
            $chartDataset->whereBetween('individual_date', [$startDate, $endDate]);
        }

        return $chartDataset->orderBy('individual_date', 'asc')
            ->get(['individual_date', 'impression', 'complete_views', 'active_viewable_impression', 'clicks'])
            ->map(function ($item) {
                return [
                    date('M j', strtotime($item->individual_date)),
                    $item->impression,
                    $item->complete_views,
                    $item->active_viewable_impression,
                    $item->clicks
                ];
            })
            ->toArray();
    }

}
