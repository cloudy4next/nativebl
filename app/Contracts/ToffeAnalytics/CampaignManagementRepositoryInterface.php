<?php declare(strict_types=1);

namespace App\Contracts\ToffeAnalytics;


interface CampaignManagementRepositoryInterface
{
    function getAllCampaignRecordById(int $id);
    function storeDailyCampaign(array $campaignData);
    function checkIdDateRangeExits(int $id, $startDate, $endDate): bool|array;
    function campaignReportFetchSaveService(int $lineItemId, \DateTime $startDate, \DateTime $endDate, bool $isPuaused = false);
    function getLastSavedDate(int $id): string|null;
}
