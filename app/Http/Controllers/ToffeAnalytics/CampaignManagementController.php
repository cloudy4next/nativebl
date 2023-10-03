<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;
use NativeBL\Field\ButtonField;
use NativeBL\Field\Field;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;
use Auth;

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
            ButtonField::init(ButtonField::EDIT)->linkToRoute('toffee.single.campaign.detail', function ($row) {
                return [
                    'id' => $row['id'],
                    'startDate' => urlencode($row['startDateTime']),
                    'endDate' => urlencode($row['endDateTime']),
                    'impression' => urlencode($row['impression']),
                    'clicks' => urlencode($row['clicks']),
                    'ctr' => urlencode($row['ctr']),
                    'view' => urlencode($row['complete']),
                    'status' => $row['status'],
                    'name' => urlencode($row['name']),
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
            TextField::init('status','Status'),
            TextField::init('name','Campaign Name')
        ];
        if (Auth::user()->isSuperAdmin()) {
            array_push($fields, TextField::init('agency'), TextField::init('brand'));
        }
        if (Auth::user()->isbrand()) {
            array_push($fields, TextField::init('brand'));
        } elseif (Auth::user()->isAgency()) {
            array_push($fields, TextField::init('agency'));
        }

        $this->getFilter($fields);
    }


    public function allCampaign()
    {
        $this->initGrid(
            [
                Field::init('status', 'Status'),
                Field::init('name', 'Campaign Name'),
                Field::init('brandName', 'Brand'),
                Field::init('startDate', 'Start Date'),
                Field::init('endDate', 'End Date'),
                Field::init('goal', 'Goal'),
                Field::init('impression', 'Impression'),
                Field::init('clicks', 'Clicks (%)'),
                Field::init('ctr', 'CTR (%)'),
                Field::init('complete', 'Complete'),
            ],
            pagination: 10
        );

        return view('home.toffe.campaign-report.campaign');
    }

    public function campaignRangeData(Request $request)
    {
        $id = $request->input('id');
        $startDate = urldecode($request->input('startDate'));
        $endDate = urldecode($request->input('endDate'));
        $data = $this->campaignManagementInterface->getCampaignFromDateRange($id, $startDate, $endDate);
        return response()->json(['data' => $data]);

    }

}
