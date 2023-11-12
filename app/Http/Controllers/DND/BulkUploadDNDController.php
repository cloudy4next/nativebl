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
use App\Http\Requests\Common\XlsCsvUploadRequest;
use App\Models\DND\MasterDND;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\FileField;

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
            InputField::init('filename', 'File Name'),

        ];
        $this->getFilter($fields);
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init('new', 'Upload')->linkToRoute('dnd.upload')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('dnd-bulk')->createAsFormAction(),
        ];
    }


    public function uploadBulk()
    {
        $this->initGrid(
            [
                Field::init('filename'),
                Field::init('creation_date'),
                Field::init('schedule_process_time'),
                Field::init('process_end_time'),
                Field::init('record_count'),
                Field::init('pending', 'Total Pending'),
                Field::init('completed', 'Total Completed'),
                Field::init('process_status_label', 'Process Status'),
            ]
        );

        return view('home.dnd.bulk-upload-dnd')->with('channels');
    }

    public function export(Request $request)
    {

        $type = $request->type;

        $data = $this->bulkuploadDNDRepositoryInterface->getExportData();

        $view = view('home.dnd.Report.dnd-report', compact('data'));
        $filename = "DND Bulk Report-" . \Carbon\Carbon::now()->format('d-m-Y h-i-s A');

        return match ($type) {
            'pdf' => $view,
            'csv' => Excel::download(new Export($view), "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV),
            default => Excel::download(new Export($view), "{$filename}.xls", \Maatwebsite\Excel\Excel::XLS),
        };
    }

    public function getUploadView()
    {
        return view('home.dnd.file-upload');
    }

    public function saveUploadData(XlsCsvUploadRequest $request)
    {
        $file = $request->file('file');
        $shecduleDate = $request->datetime;
        $this->bulkuploadDNDRepositoryInterface->saveUplaodedData($file, $shecduleDate);

        return redirect('/dnd-bulk')->with('success', 'Uploaded SuccesFully.');
    }
}
