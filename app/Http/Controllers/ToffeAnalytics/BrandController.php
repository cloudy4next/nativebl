<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\Services\ToffeAnalytics\BrandServiceInterface;
use App\Contracts\Services\Settings\UserServiceInterface;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\ToffeAnalytics\BrandRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;
use NativeBL\Field\FileField;
use NativeBL\Field\HiddenField;
use NativeBL\Field\ChoiceField;
use NativeBL\Services\CrudBoard\GridFilter;

use App\Services\ToffeAnalytics\BrandService;
use Illuminate\Support\Facades\Redirect;

use Auth;

class BrandController extends AbstractController
{
    private BrandServiceInterface $brandService;
    public function __construct(private BrandRepositoryInterface $repo, BrandServiceInterface $brandService,
        private UserServiceInterface $userServiceInterface)
    {
        $this->brandService = $brandService;
        $this->userServiceInterface  = $userServiceInterface ;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('brand_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('brand_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('brand_detail'),
            ButtonField::init('new', 'new')->linkToRoute('brand_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('brand_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('brand_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $userList = $this->userServiceInterface->getAllUserIDNameArr();
        // dd($userList);
        $fields = [
            IdField::init('id'),
            TextField::init('name')->validate('required|max:255'),
            TextField::init('icon', 'Icon'),
            ChoiceField::init('user[]','Map User',choiceType:'checkbox', choiceList:$userList)->setCssClass('my-class'),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
        ];
        $this->getForm($fields)
            ->setName('brand_form')
            ->setMethod('post')
            ->setActionUrl(route('brand_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('name')
        ];
         $this->getFilter($fields);
    }


    public function agencies()
    {
        $this->initGrid(['name','icon'], pagination: 5)
        ;
        return view('home.toffe.Brand.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['name','icon']);
        return view('home.toffe.Brand.show');
    }

    public function edit($id)
    {
        $userList = $this->userServiceInterface->getAllUserIDNameArr();
        $brandDetails = $this->brandService->getBrandDataById($id);
        $mappedUserArray = array();
        if($brandDetails['brandUserMap']){
            foreach($brandDetails['brandUserMap'] as $userMap){
                $mappedUserArray[$userMap['id']] = $userMap['user_id'];
            }
        }

        return view('home.toffe.Brand.edit')
        ->with('brandDetails', $brandDetails)
        ->with('userList', $userList)
        ->with('mappedUserArray', $mappedUserArray)
        ;
    }

    public function delete($id)
    {
        $message = $this->brandService->delete($id);
        if($message == 1){
            return to_route('brand_list');
        }
        else{
            throw new NotFoundException($message);
        }
    }


    public function deleteBrandUserMap($id, $brandId)
    {
        $message = $this->brandService->deleteBrandUser($id);
        if($message == 1){
            return to_route('brand_edit', $brandId);
        }
        else{
            throw new NotFoundException($message);
        }
    }

    public function create_old()
    {
        $this->initForm('brand_form', route('brand_save'), 'post', ['id' => 'myForm']);

        return view('home.toffe.Brand.create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.toffe.Brand.create');
    }


    public function store(Request $request): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'icon' => 'required|string',
            'user' => 'required|array',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $message = $this->brandService->store($request);
        if($message == 1){
            if($request['id'] != null){
                return to_route('brand_edit', $request['id']);
            }
            else{
                return to_route('brand_list');
            }
        }
        else{
            throw new NotFoundException($message);
        }
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }

}
