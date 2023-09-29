<?php

namespace App\Http\Controllers\TMS;

use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\TMS\ReportServiceInterface;


class ReportController extends AbstractController
{

    private $Reportservice;
    public function __construct(ReportServiceInterface $Reportservice)
    {
        $this->Reportservice = $Reportservice;
    }

    public function getRepository()
    {
        return $this->Reportservice;
    }

    public function configureActions(): iterable
    {
        return [];
    }

    public function report()
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
        return view('home.tms.support.report');
    }
}
