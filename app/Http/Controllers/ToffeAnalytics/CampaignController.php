<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Contracts\Services\ToffeAnalytics\CampaignServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\ToffeAnalytics\CampaignRepositoryInterface;
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

use App\Services\ToffeAnalytics\CampaignService;
use App\Services\ToffeAnalytics\CommonService;
use Illuminate\Support\Facades\Redirect;

use Auth;
use URL;


class CampaignController extends AbstractController
{
    private CampaignServiceInterface $campaignService;
    public function __construct(private CampaignRepositoryInterface $repo, CampaignServiceInterface $campaignService, CommonService $commonService)
    {
        $this->campaignService = $campaignService;
        $this->commonService = $commonService;
    }



    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        $prevUrl = URL::previous() . '';
        $endSegment = explode('/', $prevUrl);
        $userFlag = end($endSegment);
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('toffee_campaign_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('toffee_campaign_delete', function ($row) {
                return ['id' => $row['id'], 'userId' => \Request::segment(2)];
            }),
            ButtonField::init('new', 'new')->linkToRoute('toffee_campaign_create',)->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('toffee_campaign_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('toffee_campaign_list')->createAsShowAction()->setIcon('fa-arrow-left'),
        ];
    }




    public function configureForm()
    {
        $toffeeAgencyList = $this->commonService->toffeeAgencyList();
        $toffeeBrandList = $this->commonService->toffeeBrandList();
        $fields = [
            IdField::init('id'),
            TextField::init('campaign_name')->validate('required|max:255')->setHtmlAttributes(['required'=>true,'maxlength'=>13,'minlength'=>6]),
            TextField::init('campaign_id', 'Campaign ID')->setHtmlAttributes(['required'=>true,'maxlength'=>100,'minlength'=>6]),
            ChoiceField::init('agency_id', 'Map Agency', choiceType: 'select', choiceList: $toffeeAgencyList)->setHtmlAttributes(['required'=>true,'maxlength'=>100,'minlength'=>6]),
            ChoiceField::init('brand_id', 'Map Brand', choiceType: 'select', choiceList: $toffeeBrandList)->setCssClass('my-class')->setHtmlAttributes(['required'=>true,'maxlength'=>100,'minlength'=>6]),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
        ];
        $this->getForm($fields)
            ->setName('toffee_campaign_form')
            ->setMethod('post')
            ->setActionUrl(route('toffee_campaign_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('campaign_name'),
            TextField::init('campaign_id'),
            TextField::init('agencyName'),
            TextField::init('brandName'),
        ];
        $this->getFilter($fields);
    }


    public function campaigns()
    {
        $this->initGrid(['campaign_name', Field::init('campaign_id', 'Campaign ID'), Field::init('agencyName', 'Agency'), Field::init('brandName', 'Brand')], pagination: 5)
        ;
        return view('home.toffe.Campaign.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['campaign_name', 'campaign_id', 'agencyName', 'brandName', 'userId']);
        return view('home.toffe.Campaign.show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('home.toffe.Campaign.edit');
    }
    public function delete($id)
    {
         $this->campaignService->delete($id);

       return  to_route('toffee_campaign_list');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.toffe.Campaign.create');
    }


    public function store(Request $request): RedirectResponse
    {

        $validator = \Validator::make($request->all(), [
            'campaign_name' => 'required|string|max:255',
            'campaign_id' => 'required|integer',
            'agency_id' => 'required|integer',
            'brand_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->campaignService->store($request);
        return to_route('toffee_campaign_list', [$request['user_id']]);
    }


}
