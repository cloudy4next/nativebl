<?php

namespace App\Services\TMS;

use App\Contracts\Services\TMS\DailyReportServiceInterface;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Models\TMS\RegisterFile;

final class DailyReportService extends AbstractNativeRepository implements DailyReportServiceInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return '';
    }
    public function getGridData(): iterable
    {
        $q = 'AIR'; // ALL , file name
        $fromReport = 'regular'; // regular , dump
        $date = '2023-08-10 16:42:09';
        $insertDate = '';
        $endDate='';
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
                $status = '-1';
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
            ->whereBetween('insert_date', [$date, $date])->get();

        return $result;

    }


}
