<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\Services\ToffeAnalytics\CampaignManagementServiceInterface;
use App\Exceptions\NotFoundException;
use App\Exports\Export;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use NativeBL\Field\Field;
use NativeBL\Field\HiddenField;
use NativeBL\Field\TextField;

class UserCampaingController extends AbstractController
{

    public function __construct(
        private readonly CampaignManagementServiceInterface $campaignManagementInterface,
        private readonly UserCampaignRepositoryInterface    $userCampaignRepositoryInterface
    )
    {
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
        $id = \Request::segment(3);

        $fields = [
            TextField::init('individual_date','Date')->setHtmlAttributes(['id' => 'daterangepicker']),
            HiddenField::init('lineitem', 'lineitem', $id)
        ];
        $this->getFilter($fields);
    }


    public function singleCampaign(Request $request)
    {
        $id = $request->id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status;
        $newCardArray = [
            'impression' => $request->impression,
            'clicks' => $request->clicks,
            'ctr' => urldecode($request->ctr),
            'view' => $request->view,
            'status' => $request->status,
            'name' => json_decode($request->name),
        ];
        if (isset($request->filters) == null) {
            $this->campaignManagementInterface->campaignReportByLineItem((int)$id, $startDate, $endDate, $status);
        }

        $this->initGrid(
            [
                Field::init('individual_date', 'Date'),
                Field::init('impression', 'Impression'),
                Field::init('clicks', 'Clicks'),
                Field::init('complete_views', 'Complete Views'),
                Field::init('active_viewable_impression', 'Active Viewable Impression'),
                Field::init('viewable_impression', 'Viewable Impression (%)')->formatValue(function ($value) {
                    return $value . " %";
                }),
                Field::init('ctr', 'CTR (%)')->formatValue(function ($value) {
                    return $value . " %";
                }),
                Field::init('completion_rate', 'Completion Rate (%)')->formatValue(function ($value) {
                    return $value . " %";
                }),

            ],
            pagination: 1000
        );
        $dateArray = getDateArray($request);
        $dateArray['id'] = \Request::segment(3);
        $dataset = $this->userCampaignRepositoryInterface->getHalfMonthData($dateArray);
        return view('home.toffe.campaign-report.campaign-single')
            ->with('data', $newCardArray)
            ->with('dataset', $dataset);

    }

    /**
     * @throws NotFoundException
     */
    public function export(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            throw new NotFoundException(['id' => 'Campaign ID Not Found']);
        }

        $dateArray = getDateArray($request);
        $startDate = $dateArray['startDate'];
        $endDate = $dateArray['endDate'];

        $type = $request->type;

        $data = $this->userCampaignRepositoryInterface->getExportData((int)$id, $startDate, $endDate);
        $view = view('home.toffe.Report.all-campaign-report', compact('data'));
        $filename = "Campaign Report [$id] " . Carbon::now()->format('d-m-Y h-i-s A');

        return match ($type) {
            'pdf' => $view,
            'csv' => Excel::download(new Export($view), "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV),
            default => Excel::download(new Export($view), "{$filename}.xls", \Maatwebsite\Excel\Excel::XLS),
        };
    }

}
