<?php

namespace App\Http\Controllers\TMS;

use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\Services\TMS\SearchServiceInterface;
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

class SearchController extends AbstractController
{

    private $searchservice;
    public function __construct(SearchServiceInterface $searchservice)
    {
        $this->searchservice = $searchservice;
    }

    public function getRepository()
    {
        return $this->searchservice;
    }

    public function configureActions(): iterable
    {
        return [];
    }

    public function search()
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
        return view('home.tms.support.search');
    }
}
