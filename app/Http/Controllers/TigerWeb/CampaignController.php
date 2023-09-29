<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\CampaignServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\CampaignRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\IdField;
use NativeBL\Field\InputField;
use NativeBL\Field\Field;
use NativeBL\Field\HiddenField;
use NativeBL\Field\DateTimeField;

use App\Services\TigerWeb\CampaignService;
use App\Services\TigerWeb\CommonService;
use Illuminate\Support\Facades\Redirect;

use Auth;

class CampaignController extends AbstractController
{
    private CampaignServiceInterface $campaignService;
    private $commonService;
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
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('campaign_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('campaign_delete'),
            // ButtonField::init(ButtonField::DETAIL)->linkToRoute('campaign_detail'),
            ButtonField::init('new', 'new')->linkToRoute('campaign_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('campaign_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('campaign_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $articles = $this->commonService->articleList();
        // dd($articles);
        $fields = [
            IdField::init('id'),
            TextField::init('title')->validate('required|max:255'),
            ChoiceField::init('article_id','Article',choiceType:'select', choiceList:$articles)->setCssClass('my-class'),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
            DateTimeField::init('start_date'),
            DateTimeField::init('end_date'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('campaign_form')
            ->setMethod('post')
            ->setActionUrl(route('campaign_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function campaigns()
    {
        $this->initGrid(['title','slug', Field::init('articleTitle','Article'), Field::init("createdBy",'Created By'), Field::init('refCampaign','Ref. Campaign'), 'start_date', 'end_date'])
        ;
        return view('home.TigerWeb.Campaign.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['title','slug','content', 'article_id', 'created_by', 'updated_by', 'start_date', 'end_date']);
        return view('home.TigerWeb.Campaign.show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('home.TigerWeb.Campaign.edit');
    }
    public function delete($id)
    {
        echo $id;
        exit();
    }
    public function create_old()
    {
        $this->initForm('campaign_form', route('campaign_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.TigerWeb.Campaign.create');
    }


    public function store(Request $request): RedirectResponse
    {
        $request['slug'] = str_replace(' ', '-', $request['title']);
        $this->campaignService->store($request);
        return to_route('campaign_list');
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
    	//$displayRecordPerPage = 10;
        try {
           // $request->display_item_per_page = isset($request->display_item_per_page)?$request->display_item_per_page:10;
            //$articles = $this->articleService->showAllArticle($request)->paginate($request->display_item_per_page);
            $campaigns = $this->campaignService->showAllCampaign($request)->get();
            dd($campaigns);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Campaigns table not found!");
            return Redirect::to('/campaign/list');
        }
    }



}
