<?php

namespace App\Services\TMS;

use App\Contracts\Services\TMS\HourlyReportServiceInterface;
use Carbon\Carbon;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Models\TMS\RegisterFile;

final class HourlyReportService extends AbstractNativeRepository implements HourlyReportServiceInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return '';
    }
    public function getGridData(): iterable
    {
        $q = 'SMSC'; // ALL , file name
        $fromReport = 'regular'; // regular , dump
        $from = "2023-08-12 14:00:00";
        $to = "2023-08-12 15:00:00";

        $operation = "push"; // download , push , encryption , decryption

        if ($fromReport == 'regular') {
            $model = 'register_files';
        } else {
            $model = 'register_files_bak';
        }

        switch ($operation) {
            case 'download':
                $opearationType = 'is_downloaded';
                break;
            case 'push':
                $opearationType = 'is_pushed';
                $status = '0';
                break;
            case 'encryption':
                $opearationType = 'is_encrypted';
                break;
            case 'decryption':
                $opearationType = 'is_decrypted';
                break;
        }
        $result = RegisterFile::select($model . '.*')
            ->join('source_file_info', $model . '.src_file_info_id', '=', 'source_file_info.id')
            ->where('source_file_info.cdr_type_name', 'LIKE', '%' . $q . '%')
            ->where($model . '.' . $opearationType, '=', $status)
            ->whereRaw('DATE_FORMAT(insert_date, "%Y-%m-%d %H:00:00") >= ? AND DATE_FORMAT(insert_date, "%Y-%m-%d %H:00:00") <= ?', [$from, $to])->get();

        return $result;

    }


}
