<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;
use NativeBL\Field\ButtonField;
use NativeBL\Field\Field;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignManagementController extends AbstractController
{
    public function __construct(private readonly CampaignManagementServiceInterface $campaignManagementInterface, private readonly CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface)
    {
    }

    public function getRepository()
    {
        return $this->campaignManagementRepositoryInterface;
    }

    public function configureActions(): iterable
    {

        return [
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('toffee.single.campaign.detail', function ($row) {
                return [
                    'id' => $row['id'],
                    'startDate' => $row['startDateTime'],
                    'endDate' => $row['endDateTime'],
                    'impression' => urlencode($row['impression']),
                    'clicks' => urlencode($row['clicks']),
                    'ctr' => urlencode($row['ctr']),
                    'view' => urlencode($row['complete']),
                    'status' => $row['status'],
                    'name' => json_encode($row['name']),
                    'lineitem' => urlencode($row['id']),
                ];
            }),
        ];
    }

    public function configureForm()
    {
        return [];
    }


    public function configureFilter(): void
    {
        $fields = [
            TextField::init('status', 'Status'),
            TextField::init('name', 'Campaign Name')
        ];
        if (Auth::user()->isSuperAdmin()) {
            array_push($fields, TextField::init('agency'), TextField::init('brandName', 'Brand'));
        }
        if (Auth::user()->isbrand()) {
            array_push($fields, TextField::init('brandName', 'Brand'));
        } elseif (Auth::user()->isAgency()) {
            array_push($fields, TextField::init('agency'));
        }

        $this->getFilter($fields);
    }


    public function allCampaign()
    {
        $this->initGrid(
            [
                Field::init('status', 'Status')->setCssClass('text-center'),
                Field::init('name', 'Campaign Name')->setCssClass('text-center'),
                Field::init('agencyName', 'Agency')->setCssClass('text-center'),
                Field::init('brandName', 'Brand')->setCssClass('text-center'),
                Field::init('startDate', 'Start Date')->setCssClass('text-center'),
                Field::init('endDate', 'End Date')->setCssClass('text-center'),
                Field::init('goal', 'Goal')->setCssClass('text-center'),
                Field::init('impression', 'Impression')->setCssClass('text-center'),
                Field::init('clicks', 'Clicks')->setCssClass('text-center'),
                Field::init('ctr', 'CTR (%)')->setCssClass('text-center'),
                Field::init('complete', 'Complete')->setCssClass('text-center'),
            ],
            pagination: 10,
            config: [
                'actionHeader' => 'View Campaign Details',
                'headerRowCssClass' => 'thead-purple',
            ]
        );
        // View Campaign Details
        return view('home.toffe.campaign-report.campaign');
    }

    public function campaignRangeData(Request $request)
    {
        $id = $request->input('id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $data = $this->campaignManagementInterface->getCampaignFromDateRange($id, $startDate, $endDate);
        return response()->json(['data' => $data]);
    }
}
