<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\FaqServiceInterface;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\FaqRepositoryInterface;
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

use App\Services\TigerWeb\FaqService;
use App\Services\TigerWeb\CommonService;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Auth;

class FaqController extends AbstractController
{
    private FaqServiceInterface $faqService;
    private $commonService;
    public function __construct(private FaqRepositoryInterface $repo, FaqServiceInterface $faqService, CommonService $commonService)
    {
        $this->faqService = $faqService;
        $this->commonService = $commonService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        $faq_type = \Request::segment(2);
        $faq_type_id = \Request::segment(3);
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('faq_edit', function ($row) {
                return [
                    \Request::segment(2),
                    \Request::segment(3),
                    $row['id']
                ];
            })->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('faq_delete', function ($row) {
                return [
                    \Request::segment(2),
                    \Request::segment(3),
                    $row['id']
                ];
            }),
            // ButtonField::init(ButtonField::DETAIL)->linkToRoute('faq_detail'),
            ButtonField::init('new', 'new')->linkToRoute('faq_create', [$faq_type, $faq_type_id])->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('faq_list', [$faq_type, $faq_type_id])->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('faq_list', [$faq_type, $faq_type_id])->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        //$faqs = $this->commonService->faqList();
        $faq_type = \Request::segment(2);
        $faq_type_id = \Request::segment(3);
        // dd($faq_type);
        $fields = [
            IdField::init('id'),
            TextField::init('question')->validate('required'),
            TextareaField::init('answer')->validate('required'),
            TextField::init('tag_name')->validate('required'),
            HiddenField::init('faq_type')->setDefaultValue($faq_type),
            HiddenField::init('ref_id')->setDefaultValue($faq_type_id),
            HiddenField::init('created_at')->setDefaultValue(date("Y-m-d H:i:s")),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
        ];
        $this->getForm($fields)
            ->setName('faq_form')
            ->setMethod('post')
            ->setActionUrl(route('faq_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('question'),
            TextField::init('answer'),
            // TextField::init('other')
        ];
        $this->getFilter($fields);
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function faqs()
    {
        $this->initGrid([
            'faq_type',
            Field::init('Title','Ref. Title'),
            Field::init('question','Question'),
            Field::init('answer','Answer')
        ]);
        return view('home.TigerWeb.Faq.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['title','slug','content', 'article_id', 'created_by', 'updated_by', 'start_date', 'end_date']);
        return view('home.TigerWeb.faq.show');
    }

    public function edit($faq_type, $faq_type_id, $id)
    {
        //dd($id);
        $this->initEdit($id);
        return view('home.TigerWeb.faq.edit');
    }
    public function delete($faq_type, $faq_type_id, $id)
    {
        $message = $this->faqService->delete($id);
        if($message == 1){
            return to_route('faq_list', ['faq_type' => $faq_type, 'faq_type_id' => $faq_type_id]);
        }
        else{
            throw new NotFoundException($message);
        }
    }
    public function create_old()
    {
        $this->initForm('faq_form', route('faq_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.TigerWeb.faq.create');
    }


    public function store(Request $request): RedirectResponse
    {
        //$form = $this->initStore($request);
        //$data = $form->getData();

        $this->faqService->store($request);
        return to_route('faq_list', ['faq_type' => $request->faq_type, 'faq_type_id' => $request->ref_id]);
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
            $faqs = $this->faqService->showAllfaq($request)->get();
            dd($faqs);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("faqs table not found!");
            return Redirect::to('/faq/list');
        }
    }



}
