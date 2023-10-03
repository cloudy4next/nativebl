<?php

namespace App\Services\ToffeAnalytics;

use App\Traits\ToffeAnalytics\AdsManagerTrait;

use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;


final class CampaignManagementService implements CampaignManagementServiceInterface
{
    use AdsManagerTrait;

    public function __construct(private readonly CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface)
    {
    }

    public function campaignReportByLineItem(int $id, string $startDate, string $endDate, string $status)
    {
        $today = $this->makeDateTime('now');
        $endDate = $this->makeDateTime($endDate);
        $newEndDate = ($today < $endDate) ? clone $today : clone $endDate;
        $newStartDate = clone $newEndDate;
        $newStartDate->modify('-6 days');

        return match ($status) {
            'PAUSED', 'COMPLETED' => $this->GetFromDbOrGoogle($id, $this->makeDateTime($startDate), $endDate, true),
            default => $this->GetFromDbOrGoogle($id, $newStartDate, $newEndDate),
        };

    }

    public function getCampaignFromDateRange(int $id, string $startDate, string $endDate)
    {
        $startDate = $this->makeDateTime($startDate);
        $endDate = $this->makeDateTime($endDate);
        return $this->GetFromDbOrGoogle($id, $startDate, $endDate);

    }

    // public function GetFromDbOrGoogle($lineItem, $startDate, $endDate, bool $isPaused = false)
    // {
    //     // dd($isPaused);
    //     $fromDb = $this->campaignManagementRepositoryInterface->checkIdDateRangeExits($lineItem, $startDate, $endDate);
    //     dd($fromDb);
    //     if ($fromDb) {
    //         return $fromDb;
    //     }
    //     return $this->campaignManagementRepositoryInterface->campaignReportFetchSaveService($lineItem, $startDate, $endDate, $isPaused);
    // }

    public function GetFromDbOrGoogle($lineItem, $startDate, $endDate, bool $isPaused = false)
    {
        $fromDb = $this->campaignManagementRepositoryInterface->checkIdDateRangeExits($lineItem, $startDate, $endDate);
        return $fromDb ?: $this->campaignManagementRepositoryInterface->campaignReportFetchSaveService($lineItem, $startDate, $endDate, $isPaused);
    }


}
