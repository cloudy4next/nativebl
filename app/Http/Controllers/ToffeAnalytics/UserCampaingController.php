<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\ToffeAnalytics\UserCampaignServiceInterface;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use NativeBL\Field\DateTimeField;
use NativeBL\Field\Field;

class UserCampaingController extends AbstractController
{

    public function __construct(
        private CampaignManagementServiceInterface $campaignManagementInterface,
        private UserCampaignRepositoryInterface $userCampaignRepositoryInterface
    ) {
        $this->campaignManagementInterface = $campaignManagementInterface;
        $this->userCampaignRepositoryInterface = $userCampaignRepositoryInterface;
    }
    public function getRepository()
    {
        return $this->userCampaignRepositoryInterface;
    }

    public function configureForm()
    {
        return [];
    }



    public function configureActions(): iterable
    {
        return [];
    }


    public function configureFilter(): void
    {
        $fields = [
            DateTimeField::init('individual_date', 'Date'),

        ];
        $this->getFilter($fields);
    }


    public function singleCampaign(Request $request)
    {
        $id = $request->id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
        $newCardArry = [
            'impression' => $request->impression,
            'clicks' => $request->clicks,
            'ctr' => $request->ctr,
            'view' => $request->view,
            'status' => $request->status,
        ];
        if (isset($request->filters) == null) {
            $this->campaignManagementInterface->campaignReportByLineItem((int) $id, $startDate, $endDate, $status);
        }

        $this->initGrid(
            [
                Field::init('individual_date', 'Date'),
                Field::init('impression', 'Impression'),
                Field::init('clicks', 'Clicks'),
                Field::init('complete_views', 'Complete Views'),
                Field::init('active_viewable_impression', 'Active Viewable Impression'),
                Field::init('viewable_impression', 'Viewable Impression %'),
                Field::init('ctr', 'CTR %'),
                Field::init('completion_rate', 'Completion Rate %'),

            ],
            pagination: 1000
        );

        return view('home.toffe.campaign-report.campaign-single')->with('data', $newCardArry);
    }


}
