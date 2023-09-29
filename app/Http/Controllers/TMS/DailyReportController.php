<?php

namespace App\Http\Controllers\TMS;

use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\TMS\DailyReportServiceInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\FileField;
use NativeBL\Field\IdField;
use NativeBL\Field\InputField;
use NativeBL\Field\Field;

class DailyReportController extends AbstractController
{

    private $dailyReportservice;
    public function __construct(DailyReportServiceInterface $dailyReportservice)
    {
        $this->dailyReportservice = $dailyReportservice;
    }

    public function getRepository()
    {
        return $this->dailyReportservice;
    }

    public function configureActions(): iterable
    {
        return [];
    }

    public function dailyReport()
    {
        $this->initGrid(['id',
        'file_name',
        'file_size',
        'file_mod_date',
        'number_of_records',
        'is_downloaded',
        'is_file_available',
        'is_decrypted',
        'is_encrypted',
        'insert_date']);
        return view('home.tms.support.daily_report');
    }
}
