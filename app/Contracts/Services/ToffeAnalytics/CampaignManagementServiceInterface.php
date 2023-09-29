<?php


namespace App\Contracts\Services\ToffeAnalytics;

use Carbon\Carbon;

interface CampaignManagementServiceInterface
{

    public function campaignReportByLineItem(int $id,string $startDate,string $endDate, string $status);
    public function getCampaignFromDateRange(int $id,string $startDate,string $endDate);


}


