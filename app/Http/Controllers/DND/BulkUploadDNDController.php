<?php

namespace App\Http\Controllers\DND;

use App\Contracts\DND\BulkUploadDNDRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Services\DND\BulkUploadDNDService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\Field;
use NativeBL\Field\InputField;
use App\Exports\Export;

class BulkUploadDNDController extends AbstractController
{
    public function __construct(private BulkUploadDNDService $bulkUploadDNDService, private BulkUploadDNDRepositoryInterface $bulkuploadDNDRepositoryInterface)
    {
        $this->bulkUploadDNDService = $bulkUploadDNDService;
        $this->bulkuploadDNDRepositoryInterface = $bulkuploadDNDRepositoryInterface;
    }

    public function getRepository()
    {

        return $this->bulkuploadDNDRepositoryInterface;
    }

    public function configureForm()
    {
        return [];
    }

    public function configureFilter(): void
    {
        $fields = [
            InputField::init('msisdn', 'MSISDN', 'number'),
        ];
        $this->getFilter($fields);
    }


    public function configureActions(): iterable
    {
        return [];
    }




    public function uploadBulk()
    {
        $this->initGrid(
            [
                Field::init('msisdn'),
                Field::init('channel_name'),
            ]
        );

        return view('home.dnd.bulk-upload-dnd')->with('channels');
    }

    public function export(Request $request)
    {
        $msisdn = $request->msisdn;
        if (empty($msisdn)) {
            throw new NotFoundException('No Data Found!!');
        }

        $dateArray = getDateArray($request);
        $startDate = $dateArray['startDate'];
        $endDate = $dateArray['endDate'];

        $type = $request->type;

        $data = $this->bulkuploadDNDRepositoryInterface->getExportData((int)$msisdn, $startDate, $endDate);
        $view = view('home.toffe.Report.all-campaign-report', compact('data'));
        $filename = "DND Bulk Report [$msisdn] " . \Carbon::now()->format('d-m-Y h-i-s A');

        return match ($type) {
            'pdf' => $view,
            'csv' => Excel::download(new Export($view), "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV),
            default => Excel::download(new Export($view), "{$filename}.xls", \Maatwebsite\Excel\Excel::XLS),
        };
    }
}
