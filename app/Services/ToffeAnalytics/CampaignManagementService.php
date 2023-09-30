<?php

namespace App\Services\ToffeAnalytics;

use App\Traits\ToffeAnalytics\AdsManagerTrait;

use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;


final class CampaignManagementService implements CampaignManagementServiceInterface
{
    use AdsManagerTrait;

    public function __construct(private CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface)
    {
        $this->campaignManagementRepositoryInterface = $campaignManagementRepositoryInterface;
    }

    public function campaignReportByLineItem(int $id, string $startDate, string $endDate, string $status)
    {
        $today = $this->makeDateTime('now');
        $endDate = $this->makeDateTime($endDate);
        $newEndDate = ($today < $endDate) ? clone $today : clone $endDate;
        $newStartDate = clone $newEndDate;
        $newStartDate->modify('-6 days');

        switch ($status) {
            case 'PAUSED':
                return $this->GetFromDbOrGoogle($id, $this->makeDateTime($startDate), $endDate, true);
            case 'COMPLETED':
                return $this->GetFromDbOrGoogle($id, $this->makeDateTime($startDate), $endDate);
            default:
                return $this->GetFromDbOrGoogle($id, $newStartDate, $newEndDate);
        }

    }

    public function getCampaignFromDateRange(int $id, string $startDate, string $endDate)
    {
        $startDate = $this->makeDateTime($startDate);
        $endDate = $this->makeDateTime($endDate);
        return $this->GetFromDbOrGoogle($id, $startDate, $endDate);

    }

    public function GetFromDbOrGoogle($lineItem, $startDate, $endDate, bool $isPaused = false)
    {
        $fromDb = $this->campaignManagementRepositoryInterface->checkIdDateRangeExits($lineItem, $startDate, $endDate);
        if ($fromDb) {
            return true;
        }
        $this->campaignManagementRepositoryInterface->campaignReportFetchSaveService($lineItem, $startDate, $endDate, $isPaused);
        return false;
    }


}
