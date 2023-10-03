<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\Services\ToffeAnalytics\AgencyServiceInterface;
use App\Contracts\Services\Settings\UserServiceInterface;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\ToffeAnalytics\AgencyRepositoryInterface;
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
use NativeBL\Field\Field;
use NativeBL\Services\CrudBoard\GridFilter;

use App\Services\ToffeAnalytics\AgencyService;
use App\Services\ToffeAnalytics\CommonService;
use Illuminate\Support\Facades\Redirect;

use Auth;

class AgencyController extends AbstractController
{
    private AgencyServiceInterface $agencyService;
    public function __construct(
        private AgencyRepositoryInterface $repo,
        AgencyServiceInterface $agencyService,
        CommonService $commonService,
        private UserServiceInterface $userServiceInterface
    ) {
        $this->userServiceInterface = $userServiceInterface;
        $this->agencyService = $agencyService;
        $this->commonService = $commonService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('agency_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('agency_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('agency_detail'),
            ButtonField::init('new', 'new')->linkToRoute('agency_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('agency_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('agency_list')->createAsShowAction()->setIcon('fa-arrow-left'),
        ];
    }




    public function configureForm(): void
    {
        $userList = $this->userServiceInterface->getAllUserIDNameArr();
        $fields = [
            IdField::init('id'),
            TextField::init('name')->validate('required|max:255')->setHtmlAttributes(['required'=>true,'maxlength'=>100,'minlength'=>6]),
            TextField::init('icon', 'Icon')->setHtmlAttributes(['required'=>true,'maxlength'=>100,'minlength'=>6]),
            ChoiceField::init('user[]', 'Map User', choiceType: 'checkbox', choiceList: $userList)->setCssClass('my-class'),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
        ];
        $this->getForm($fields)
            ->setName('agency_form')
            ->setMethod('post')
            ->setActionUrl(route('agency_save'));
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
        $this->initGrid([Field::init('name', 'Name'), Field::init('icon', 'Icon')], pagination: 5);
        return view('home.toffe.Agency.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['name', 'icon']);
        return view('home.toffe.Agency.show');
    }

    public function edit($id)
    {
        $userList = $this->userServiceInterface->getAllUserIDNameArr();
        $agencyDetails = $this->agencyService->getAgencyDataById($id);
        $mappedUserArray = array();
        if ($agencyDetails['agencyUserMap']) {
            foreach ($agencyDetails['agencyUserMap'] as $userMap) {
                $mappedUserArray[$userMap['id']] = $userMap['user_id'];
            }
        }

        return view('home.toffe.Agency.edit')
            ->with('agencyDetails', $agencyDetails)
            ->with('userList', $userList)
            ->with('mappedUserArray', $mappedUserArray)
        ;
    }

    public function delete($id)
    {
        $message = $this->agencyService->delete($id);
        if ($message == 1) {
            return to_route('agency_list');
        } else {
            throw new NotFoundException($message);
        }
    }

    public function deleteAgencyUserMap($id, $agencyId)
    {
        $message = $this->agencyService->deleteAgencyUser($id);
        if ($message == 1) {
            return to_route('agency_edit', $agencyId);
        } else {
            throw new NotFoundException($message);
        }
    }

    public function create_old()
    {
        $this->initForm('vas_cp_form', route('agency_save'), 'post', ['id' => 'myForm']);

        return view('home.toffe.Agency.create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.toffe.Agency.create');
    }


    public function store(Request $request): RedirectResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'icon' => 'required|string',
            'user' => 'required|array',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $message = $this->agencyService->store($request);
        if ($message == 1) {
            if ($request['id'] != null) {
                return to_route('agency_edit', $request['id']);
            } else {
                return to_route('agency_list');
            }
        } else {
            throw new NotFoundException($message);
        }

    }


}
