<?php

namespace App\Services\TMS;

use App\Contracts\Services\TMS\SearchServiceInterface;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Models\TMS\RegisterFile;
use App\Contracts\TMS\SearchRepositoryInterface;

final class SearchService extends AbstractNativeRepository implements SearchServiceInterface, CrudGridLoaderInterface
{

    private $searchRepositoryInterface;
    public function __construct(SearchRepositoryInterface $searchRepositoryInterface)
    {
        $this->searchRepositoryInterface = $searchRepositoryInterface;
    }
    public function getModelFqcn(): string
    {
        return '';
    }
    public function getGridData(): iterable
    {
        $q = 'SMSC';
        $result = RegisterFile::select('register_files.*', 'source_file_info.*')
            ->join('source_file_info', 'register_files.src_file_info_id', '=', 'source_file_info.id')
            ->where('source_file_info.cdr_type_name', 'LIKE', '%' . $q . '%')->get();
        return $result;

    }
}
