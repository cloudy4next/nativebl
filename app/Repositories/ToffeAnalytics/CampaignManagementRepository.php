<?php

declare(strict_types=1);


namespace App\Repositories\ToffeAnalytics;


use App\Models\ToffeAnalytics\ToffeeBrand;
use Google\AdsApi\AdManager\v202308\Column;
use Google\AdsApi\AdManager\v202308\Dimension;
use Google\AdsApi\AdManager\v202308\DimensionAttribute;
use Google\AdsApi\AdManager\v202308\ReportQuery;
use Google\AdsApi\AdManager\v202308\DateRangeType;
use Google\AdsApi\AdManager\v202308\DateTime;
use Google\AdsApi\AdManager\v202308\ExportFormat;
use Google\AdsApi\AdManager\v202308\ReportJob;
use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;
use App\Models\ToffeAnalytics\CampaginReport;
use App\Models\ToffeAnalytics\ToffeeAgency;
use App\Models\ToffeAnalytics\ToffeeCampaign;
use App\Traits\ToffeAnalytics\AdsManagerTrait;
use Google\AdsApi\AdManager\v202308\ReportJobStatus;
use Google\AdsApi\AdManager\v202308\ServiceFactory;
use Google\AdsApi\AdManager\Util\v202308\AdManagerDateTimes;
use Google\AdsApi\AdManager\Util\v202308\StatementBuilder;
use Illuminate\Support\Collection;
use NativeBL\Repository\AbstractNativeRepository;
use Auth;
use Illuminate\Database\Eloquent\Builder;


class CampaignManagementRepository extends AbstractNativeRepository implements CampaignManagementRepositoryInterface
{
    use AdsManagerTrait;


    public function getModelFqcn(): string
    {
        return '';
    }

    public function getGridData(array $filters = []): iterable
    {
        return $this->prepareArray();
    }


    public function prepareArray()
    {
        $newObject = array();
        $imression = null;
        $clicks = null;
        $crt = null;
        $complete = null;

        if (Auth::user()->camapign() == null) {
            return [];
        }
        $LineItemArr = $this->getLineItemByAgency(Auth::user()->camapign());
        foreach ($LineItemArr as $LineItem) {

            $id = $LineItem->getId();
            $status = $LineItem->getStatus();
            $name = $LineItem->getName(); // Campaign name.......
            //  $brandName = $LineItem->getOrderName(); //brandName ...........

            $startDateTime = $LineItem->getStartDateTime();
            if ($startDateTime != null) {
                $hiddenStartTime = $this->googleDateTimeToString($startDateTime);
                $startDate = $startDateTime->getDate();
                $startDate = $this->customDateTimeFormat($startDate->getDay(), $startDate->getMonth(), $startDate->getYear());
            }

            $endDateTime = $LineItem->getEndDateTime();
            if ($endDateTime != null) {
                $hiddenEndTime = $this->googleDateTimeToString($endDateTime);
                $endDate = $endDateTime->getDate();
                $endDate = $this->customDateTimeFormat($endDate->getDay(), $endDate->getMonth(), $endDate->getYear());
            }
            $progress = $LineItem->getDeliveryIndicator();
            if ($progress != null) {
                $progress = $progress->getActualDeliveryPercentage();
            }
            $goal = number_format($LineItem->getPrimaryGoal()->getUnits());
            $impressionsDelivered = $LineItem->getStats();
            if ($impressionsDelivered != null) {

                $imression = number_format($impressionsDelivered->getImpressionsDelivered());
                $clicks = number_format($impressionsDelivered->getClicksDelivered());
                $newImpression = str_replace(',', '', $imression);
                $newCrt = str_replace(',', '', $clicks);
                $calculatectr = (float) ((int) $newCrt / (int) $newImpression);
                $crt = number_format(($calculatectr * 100), 2) . '%';
                $complete = number_format($impressionsDelivered->getVideoCompletionsDelivered());
            }
            $campignItem = ToffeeCampaign::where('campaign_id', $id)->first();
            $brandName = $this->getBrandName($campignItem->brand_id)->name;
            $agencyName = $this->getAgencyName($campignItem->agency_id)->name;


            $newObject[] = [
                'id' => $id,
                'status' => $status,
                'name' => $name,
                'brandName' => (string) $brandName,
                'agencyName' => (string) $agencyName,

                'startDate' => $startDate ?? null,
                'endDate' => $endDate ?? null,
                'goal' => (string) $goal ?? null,
                'impression' => (string) $imression ?? null,
                'clicks' => (string) $clicks,
                'ctr' => (string) $crt,
                'startDateTime' => $hiddenStartTime ?? null,
                'endDateTime' => $hiddenEndTime ?? null,
                'complete' => $complete ?? null
            ];
        }
        return $newObject;
    }

    public function getLineItemByAgency($ids)
    {
        $serviceFactory = new ServiceFactory();
        $lineItemService = $serviceFactory->createLineItemService($this->getAdsSession());

        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $statementBuilder = (new StatementBuilder())
            ->where('id IN (:ids)')
            ->orderBy('id DESC')
            ->limit($pageSize)
            ->withBindVariableValue('ids', $ids);


        $testLineItemArray = [];

        $totalResultSetSize = 0;
        do {
            $page = $lineItemService->getLineItemsByStatement(
                $statementBuilder->toStatement()
            );

            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $lineItem) {
                    $testLineItemArray[$i++] =
                        $lineItem;
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);
        return $testLineItemArray;
    }

    public function campaignReportFetchSaveService(int $lineItemId, \DateTime $startDate, \DateTime $endDate, bool $isPuaused = false)
    {

        $reportService = (new ServiceFactory())->createReportService($this->getAdsSession());
        $reportQuery = (new ReportQuery())
            ->setDimensions([Dimension::LINE_ITEM_ID, Dimension::LINE_ITEM_NAME, Dimension::DATE])
            ->setDimensionAttributes([DimensionAttribute::LINE_ITEM_GOAL_QUANTITY])
            ->setColumns([
                Column::AD_SERVER_IMPRESSIONS,
                Column::AD_SERVER_CLICKS,
                Column::AD_SERVER_CTR,
                Column::AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS,
                Column::VIDEO_VIEWERSHIP_COMPLETION_RATE,
                Column::AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE,
                Column::VIDEO_VIEWERSHIP_COMPLETE,

            ]);
        if ($isPuaused) {
            $reportQuery->setDateRangeType(DateRangeType::REACH_LIFETIME);
        } else {
            $reportQuery->setDateRangeType(DateRangeType::CUSTOM_DATE)
                ->setStartDate(AdManagerDateTimes::fromDateTime($startDate)->getDate())
                ->setEndDate(AdManagerDateTimes::fromDateTime($endDate)->getDate());
        }

        $statementBuilder = (new StatementBuilder())
            ->where('LINE_ITEM_ID = :lineItemId')
            ->withBindVariableValue('lineItemId', $lineItemId);
        $reportQuery->setStatement($statementBuilder->toStatement());

        $reportJob = (new ReportJob())->setReportQuery($reportQuery);
        $reportJob = $reportService->runReportJob($reportJob);

        set_time_limit(120);
        while ($reportService->getReportJobStatus($reportJob->getId()) !== ReportJobStatus::COMPLETED) {
            sleep(5);
        }

        $reportDownloadUrl = $reportService->getReportDownloadUrl($reportJob->getId(), ExportFormat::CSV_DUMP);
        $this->prepareReportArray(gzdecode(file_get_contents($reportDownloadUrl)));

        return $this->getAllCampaignRecordById($lineItemId);
    }

    public function prepareReportArray(string $url): void
    {
        $lines = explode("\n", $url);
        foreach ($lines as $line) {
            if (!empty($line)) {
                $this->storeDailyCampaign(str_getcsv($line));
            }
        }
    }

    public function storeDailyCampaign($campaginData): void
    {
        try {
            if (is_numeric($campaginData[0])) {

                $this->deleteDateinSameDay($campaginData[2], (int) $campaginData[0]);

                $report = new CampaginReport();
                $report->campaign_id = $campaginData[0];
                $report->individual_date = $campaginData[2];
                $report->impression = number_format((float) $campaginData[4]);
                $report->clicks = number_format((float) $campaginData[5]);
                $report->ctr = number_format(((float) $campaginData[6] * 100), 2);
                $report->active_viewable_impression = number_format((float) $campaginData[7]);
                $report->completion_rate = number_format(((float) $campaginData[8] * 100), 2);
                $report->viewable_impression = number_format(((float) $campaginData[9] * 100), 2);
                $report->complete_views = number_format((float) $campaginData[10]);
                $report->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function deleteDateinSameDay(string $date, int $id): void
    {
        CampaginReport::where('campaign_id', $id)->where('individual_date', $date)->delete();
    }

    public function getAllCampaignRecordById($id)
    {
        return CampaginReport::where("campaign_id", $id)->get()->toArray();
    }

    public function checkIdDateRangeExits(int $id, $startDate, $endDate): bool|array
    {
        $formattedData = [];

        $campaignReports = CampaginReport::where("campaign_id", $id)
            ->whereBetween('individual_date', [$startDate, $endDate])
            ->get();
        if ($campaignReports->isEmpty()) {
            return false;
        }

        $allReports = $this->getAllCampaignRecordById($id);

        foreach ($allReports as $report) {
            $formattedData[] = [
                'individual_date' => $report['individual_date'],
                'impression' => $report['impression'],
                'clicks' => $report['clicks'],
                'complete_views' => $report['complete_views'],
                'active_viewable_impression' => $report['active_viewable_impression'],
                'viewable_impression' => number_format((float) $report['viewable_impression'], 2),
                'ctr' => number_format((float) $report['ctr'], 2),
                'completion_rate' => number_format((float) $report['completion_rate'], 2),
            ];
        }

        return $formattedData;
    }

    public function applyFilterData(Collection $data, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                $data = $data->filter(function ($item) use ($field, $value) {
                    return stripos($item[$field], $value) !== false;
                });
            }
        }
        return $data;
    }

    public function getBrandName($id)
    {
        return ToffeeBrand::where('id', $id)->first('name');
    }


    public function getAgencyName($id)
    {
        return ToffeeAgency::where('id', $id)->first('name');
    }


    public function getLastSavedDate(int $id): string|null
    {
        return CampaginReport::where('campaign_id', $id)->latest('individual_date')
            ->value('individual_date');
    }
}
