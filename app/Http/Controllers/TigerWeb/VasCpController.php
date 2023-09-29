<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\VasCpServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\VasCpRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\VasCpService;
use Illuminate\Support\Facades\Redirect;

class VasCpController extends AbstractController
{
    private VasCpServiceInterface $vasCpService;
    public function __construct(private VasCpRepositoryInterface $repo, VasCpServiceInterface $vasCpService)
    {
        $this->vasCpService = $vasCpService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('vas_cp_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('vas_cp_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('vas_cp_detail'),
            ButtonField::init('new', 'new')->linkToRoute('vas_cp_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('vas_cp_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('vas_cp_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('name')->validate('required|max:255'),
            TextField::init('address'),
            TextField::init('contact'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('vas_cp_form')
            ->setMethod('post')
            ->setActionUrl(route('vas_cp_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function vasCp()
    {
        $this->initGrid(['name','address','contact'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['name','address','contact']);
        return view('show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('edit');
    }
    public function delete($id)
    {
        echo $id;
        exit();
    }
    public function create_old()
    {
        $this->initForm('vas_cp_form', route('vas_cp_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('create');
    }


    public function store(Request $request): RedirectResponse
    {
        $form = $this->initStore($request);
        //print_r($request->all()); exit();
        // $validated = $request->validate([
        //     'full_name' => 'required|max:255',
        //     'msisdn' => 'required',
        // ]);
        $data = $form->getData();
        return to_route('vas_cp_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            // $request['search_text'] = "Daily News 1";
            $vasCp = $this->vasCpService->showAllVasCp($request)->get();
            dd($vasCp);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Vas cp table not found!");
            return Redirect::to('/vas-cp/list');
        }
    }




}
