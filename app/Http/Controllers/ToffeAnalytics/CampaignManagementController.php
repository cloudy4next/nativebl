<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\ToffeAnalytics\CampaignManagementRepositoryInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;

use App\Exceptions\NotFoundException;
use App\Contracts\ToffeAnalytics\AgencyRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\TextareaField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;
use NativeBL\Field\FileField;
use NativeBL\Field\HiddenField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Services\CrudBoard\GridFilter;

class CampaignManagementController extends AbstractController
{
    public function __construct(private CampaignManagementServiceInterface $campaignManagementInterface, private CampaignManagementRepositoryInterface $campaignManagementRepositoryInterface)
    {
        $this->campaignManagementInterface = $campaignManagementInterface;
        $this->campaignManagementRepositoryInterface = $campaignManagementRepositoryInterface;
    }
    public function getRepository()
    {
        return $this->campaignManagementRepositoryInterface;
    }

    public function configureForm()
    {
        return [];
    }



    public function configureActions(): iterable
    {


        return [
            ButtonField::init('new', 'new')->linkToRoute('toffee.all.campaign.list')->createAsCrudBoardAction(),

            ButtonField::init(ButtonField::EDIT)->linkToRoute('toffee.single.campaign.detail', function ($row) {
                return ['id' => $row['id'], 'startDate' => $row['startDateTime'], 'endDate' => $row['endDateTime'], 'status' => $row['status']];
            }),
        ];

    }


    public function configureFilter(): void
    {
        $fields = [
            TextField::init('status'),
            TextField::init('brand'),
            TextField::init('agency'),
        ];
        $this->getFilter($fields);
    }


    public function allCampaign()
    {
        $this->initGrid([
            'status',
            'brand',
            'agency',
            'startDate',
            'endDate',
            'goal',
            'impression',
            'clicks',
            'ctr',
            'complete'
        ]);
        return view('home.toffe.campaign');
    }


    public function show(Request $request)
    {

        $id = $request->id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
        $singleItemReport = $this->campaignManagementInterface->campaignReportByLineItem((int) $id, $startDate, $endDate, $status);
        return view('home.toffe.single-lineitem-report')->with('data', $singleItemReport);

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
