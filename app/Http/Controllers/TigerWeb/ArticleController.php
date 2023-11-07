<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleServiceInterface;
use App\Exceptions\NotFoundException;
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
use NativeBL\Field\FileField;

use App\Services\TigerWeb\ArticleService;
use App\Services\TigerWeb\CommonService;
use Illuminate\Support\Facades\Redirect;
use Session;

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
        // dd(\Request::segment(2));
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('article_edit')->setHtmlAttributes(['title' => 'Edit']),
            // ButtonField::init(ButtonField::DELETE)->linkToRoute('article_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_detail')->setHtmlAttributes(['title' => 'View']),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_view_detail')->setHtmlAttributes(['title' => 'Agent View'])->setIcon('fa-eye')->addCssClass('btn-success'),
            ButtonField::init('faq')->linkToRoute('faq_list', function ($row) {
                return ['ARTICLE', $row['id']];
            })->addCssClass('btn-success')->setIcon('fa-question')->setHtmlAttributes(['title' => 'FAQ'])->displayIf(fn($row): bool => $row['review_status'] != 'ARCHIVED'),
            // review_status != ARCHIVED
            ButtonField::init('archive')->linkToRoute('article_archive', function ($row) {
                return $row['id'];
            })->addCssClass('btn-warning')->setIcon('fa fa-file-archive-o')->setHtmlAttributes(['title' => 'Archive'])->displayIf(fn($row): bool => $row['review_status'] != 'ARCHIVED'),
            ButtonField::init('new', 'new')->linkToRoute('article_create')->createAsCrudBoardAction()->iconForNew(),
            ButtonField::init('submit', 'Correction')->createAsFormSubmitAction()->setHtmlAttributes(['name' => 'correction'])->addCssClass(' btn-success')->displayIf(fn($row): bool => \Request::segment(2) == 'edit'),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('article_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('article_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];

    }




    public function configureForm(): void
    {
        $articleCategories = $this->commonService->articleCategoryList();
        // dd(Session::get('role'));
        // dd($articleCategories);
        $fields = [
            IdField::init('id'),
            TextField::init('title')->setHtmlAttributes(['required' => true, 'maxlength' => 255, 'minlength' => 2]),
            TextField::init('article_category_id'),
            ChoiceField::init('article_category_id', 'Article Category', choiceType: 'select', choiceList: $articleCategories)->setCssClass('my-class')->setHtmlAttributes(['required' => true]),
            TextField::init('service_manager'),
            TextField::init('call_disposition_code'),
            TextField::init('complaint_path'),
            TextField::init('tag_name')->setHtmlAttributes(['required' => true, 'maxlength' => 255, 'minlength' => 2]),
            FileField::init('file', 'Gallery'),
            DateTimeField::init('start_date'),
            DateTimeField::init('end_date'),
            TextareaField::init('link_redirection', 'Link Redirection', rows: 2)
                ->setLayoutClass('col-md-12 pb-12')->setDefaultValue('<a href="https://www.google.com/" target="_blank"> Click Here 1</a><br>
                    <a href="https://www.google.com/" target="_blank"> Click Here 2</a><br>'),
            TextareaField::init('content', 'Content', rows: 2)
                ->setLayoutClass('col-md-12 pb-5')
                ->setHtmlAttributes(['id' => 'editor']),
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
            TextField::init('review_status', 'Status')
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
        $this->initGrid(['title', 'slug', Field::init('articleCategoryTitle', 'Article Category'), 'complaint_path', Field::init('parentArticle', 'Parent'), Field::init('review_status', 'Status'), Field::init("createdBy", 'Created By'), Field::init("start_date",'Start Date')->formatValue(fn($value) => ($value != null)?(date("F j, Y", strtotime($value))):''), Field::init("end_date",'End Date')->formatValue(fn($value) => ($value != null)?(date("F j, Y", strtotime($value))):'')], pagination: 10)
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

    public function viewArticle($id)
    {
        $articleDetails = $this->articleService->details($id);
        return view('home.TigerWeb.Article.view-article', compact('articleDetails'));
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
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'article_category_id' => 'required|string',
            'tag_name' => 'required|string',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $request['slug'] = str_replace(' ', '-', $request['title']);
        $request['review_status'] = "APPROVED";
        $message = $this->articleService->store($request);
        if ($message == 1) {
            return to_route('article_list');
        } else {
            throw new NotFoundException($message);
        }

        return to_route('article_list');
    }

    public function article_review_submit(Request $request): RedirectResponse
    {
        $this->articleService->article_review_submit($request);
        return to_route('article_view_detail', $request['article_id']);
    }

    public function articleArchive($id)
    {
        $this->articleService->articleArchive($id);
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
