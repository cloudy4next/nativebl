<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\VasServiceOptionServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\VasServiceOptionRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\VasServiceOptionService;
use Illuminate\Support\Facades\Redirect;

class VasServiceOptionController extends AbstractController
{
    private VasServiceOptionServiceInterface $vasServiceOptionService;
    public function __construct(private VasServiceOptionRepositoryInterface $repo, VasServiceOptionServiceInterface $vasServiceOptionService)
    {
        $this->vasServiceOptionService = $vasServiceOptionService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('vas_service_option_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('vas_service_option_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('vas_service_option_detail'),
            ButtonField::init('new', 'new')->linkToRoute('vas_service_option_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('vas_service_option_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('vas_service_option_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('vas_service_id', 'VAS Service'),
            TextField::init('channel'),
            TextField::init('activation'),
            TextField::init('deactivation'),
            TextField::init('created_by'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('vas_service_option_form')
            ->setMethod('post')
            ->setActionUrl(route('vas_service_option_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function vasServiceOptions()
    {
        $this->initGrid(['vas_service_id','channel','activation','deactivation', 'created_by'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['vas_service_id','channel','activation','deactivation', 'created_by']);
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
        $this->initForm('vas_service_option_form', route('vas_service_option_save'), 'post', ['id' => 'myForm']);

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
        return to_route('vas_service_option_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            // $request['search_text'] = "Daily News 1";
            $vasServiceOptions = $this->vasServiceOptionService->showAllVasServiceOption($request)->get();
            dd($vasServiceOptions);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Vas Service Option table not found!");
            return Redirect::to('/vas-service-option/list');
        }
    }




}
