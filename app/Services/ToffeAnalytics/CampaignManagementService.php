<?php

namespace App\Services\ToffeAnalytics;

use App\Traits\ToffeAnalytics\AdsManagerTrait;

use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;


final class CampaignManagementService implements CampaignManagementServiceInterface
{
    use AdsManagerTrait;

    private CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface;

    public function __construct(CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface)
    {
        $this->campaignManagementRepositoryInterface = $campaignManagementRepositoryInterface;
    }

    public function campaignReportByLineItem(int $id, string $startDate, string $endDate, string $status)
    {
        $today = $this->makeDateTime('now');
        $endDate = $this->makeDateTime($endDate);
        $newEndDate = min($today, $endDate);
        $pauseCompleteDate = $this->makeDateTime($startDate);
        /*
        | Campaign Status: Delivering 🛒
        | if no data exits then pull from startdate to today.
        | if exits then pull from lastSavedDate to today.
        | if last save data is today then it will fetch latest data on each call. 😵
        */
        $lastSavedDate = $this->campaignManagementRepositoryInterface->getLastSavedDate($id);

        if ($lastSavedDate != null) {
            $startDate = $lastSavedDate;
        }
        return match ($status) {
            'PAUSED', 'COMPLETED' => $this->getFromDbOrGoogle($id, $pauseCompleteDate, $endDate, true),
            default => $this->getFromDbOrGoogle($id, $this->makeDateTime($startDate), $newEndDate),
        };


    }

    public function getCampaignFromDateRange(int $id, string $startDate, string $endDate)
    {
        $startDate = $this->makeDateTime($startDate);
        $endDate = $this->makeDateTime($endDate);
        return $this->getFromDbOrGoogle($id, $startDate, $endDate);

    }
    public function getFromDbOrGoogle($lineItem, $startDate, $endDate, bool $isPaused = false)
    {
        $fromDb = $this->campaignManagementRepositoryInterface->checkIdDateRangeExits($lineItem, $startDate, $endDate);
        return $fromDb ?: $this->campaignManagementRepositoryInterface->campaignReportFetchSaveService($lineItem, $startDate, $endDate, $isPaused);
    }


}
