<?php declare(strict_types=1);

namespace App\Contracts\ToffeAnalytics;

interface UserCampaignRepositoryInterface
{
    function getHalfMonthData(array $data);
    function getExportData(int $id, $startDate, $endDate);
}
