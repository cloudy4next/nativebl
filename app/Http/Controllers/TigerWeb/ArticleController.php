<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleServiceInterface;
use App\Models\TigerWeb\ArticleReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\ArticleRepositoryInterface;
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

use App\Services\TigerWeb\ArticleService;
use App\Services\TigerWeb\CommonService;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends AbstractController
{
    private ArticleServiceInterface $articleService;
    private $commonService;
    public function __construct(private ArticleRepositoryInterface $repo, ArticleServiceInterface $articleService, CommonService $commonService)
    {
        $this->articleService = $articleService;
        $this->commonService = $commonService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('article_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('article_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_detail'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('faq_list',  function ($row) {
                return ['ARTICLE', $row['id']];
            }),
            ButtonField::init('new', 'new')->linkToRoute('article_create')->createAsCrudBoardAction(),
            ButtonField::init('submit', 'Correction')->createAsFormSubmitAction()->setHtmlAttributes(['name'=>'correction', 'value' => 'correction'])->addCssClass('btn btn-success'),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('article_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('article_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $articleCategories = $this->commonService->articleCategoryList();
        // dd($articleCategories);
        $fields = [
            IdField::init('id'),
            TextField::init('title')->validate('required|max:255'),
            TextField::init('article_category_id'),
            ChoiceField::init('article_category_id','Article Category',choiceType:'select', choiceList:$articleCategories)->setCssClass('my-class'),
            TextareaField::init('content'),
            TextField::init('complaint_path'),
            TextField::init('tag_name'),
            DateTimeField::init('start_date'),
            DateTimeField::init('end_date'),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('article_form')
            ->setMethod('post')
            ->setActionUrl(route('article_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('title'),
            TextField::init('complaint_path'),
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

    public function articles()
    {
        $this->initGrid(['title', 'slug', Field::init('articleCategoryTitle','Article Category'), 'complaint_path', Field::init('parentArticle','Parent'), Field::init("createdBy",'Created By'), 'start_date', 'end_date'])
        ;
        return view('home.TigerWeb.Article.list');
    }

    public function show($id)
    {
        // $this->initShow($id, ['title','article_category_id','slug','content', 'complaint_path', 'review_status', 'created_by', 'updated_by', 'start_date', 'end_date']);

        $articleDetails = $this->articleService->details($id);
        // dd($articleDetails);
        return view('home.TigerWeb.Article.show', compact('articleDetails'));
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('home.TigerWeb.Article.edit');
    }
    public function delete($id)
    {
        echo $id;
        exit();
    }
    public function create_old()
    {
        $this->initForm('article_form', route('article_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.TigerWeb.Article.create');
    }


    public function store(Request $request): RedirectResponse
    {
        // dd($request->button);
        $request['slug'] = str_replace(' ', '-', $request['title']);
        $request['review_status'] = "APPROVED";
        $this->articleService->store($request);
        return to_route('article_list');
    }

    public function article_review_submit(Request $request): RedirectResponse
    {
        $this->articleService->article_review_submit($request);
        return to_route('article_list');
    }

    public function correction(Request $request): RedirectResponse
    {
        dd($request);
        $request['slug'] = str_replace(' ', '-', $request['title']);
        $request['review_status'] = "APPROVED";
        $this->articleService->store($request);
        return to_route('article_list');
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            $articles = $this->articleService->showAllArticle($request)->get();
            dd($articles);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Articles table not found!");
            return Redirect::to('/article/list');
        }
    }




}
