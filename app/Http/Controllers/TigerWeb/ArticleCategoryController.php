<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleCategoryServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\ArticleCategoryRepositoryInterface;
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

use Auth;
use Session;

use App\Services\TigerWeb\ArticleCategoryService;
use Illuminate\Support\Facades\Redirect;

class ArticleCategoryController extends AbstractController
{
    private ArticleCategoryServiceInterface $articleCategoryService;
    public function __construct(private ArticleCategoryRepositoryInterface $repo, ArticleCategoryServiceInterface $articleCategoryService)
    {
        $this->articleCategoryService = $articleCategoryService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('article_category_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('article_category_delete'),
            // ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_category_detail'),
            ButtonField::init('new', 'new')->linkToRoute('article_category_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('article_category_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('article_category_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('title')->validate('required|max:255'),
            // TextField::init('slug')->validate('required|max:255'),
            ChoiceField::init('service_type','Service Type',choiceType:'select', choiceList:['ARTICLE'=>'ARTICLE','CAMPAIGN'=>'CAMPAIGN','VAS'=>'VAS'])->setCssClass('my-class'),
            TextareaField::init('description', 'Description'),
            HiddenField::init('created_by')->setDefaultValue(Auth::user()->id),
            // IdField::init('created_by', '', value:[Auth::user()->id]),
            // TextField::init('created_by'),
            // InputField::init('start_date','Start Date', "date"),
            // InputField::init('end_date','End Date', "date")
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('article_category_form')
            ->setMethod('post')
            ->setActionUrl(route('article_category_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('title'),
            TextField::init('service_type'),
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

    public function articleCategories()
    {
        $this->initGrid(['title','slug','service_type','description', Field::init('parentCategory','Parent'), Field::init("createdBy",'Created By'), 'start_date', 'end_date'])
        ;
        return view('home.tigerweb.articlecategory.list');
    }

    public function show($id)
    {
        $this->initShow($id, ['title','slug','service_type','description', 'ref_id', 'created_by', 'start_date', 'end_date']);
        return view('home.tigerweb.ArticleCategory.show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('home.TigerWeb.ArticleCategory.edit');
    }
    public function delete($id)
    {
        echo $id;
        exit();
    }
    public function create_old()
    {
        $this->initForm('article_category_form', route('article_category_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.TigerWeb.ArticleCategory.create');
    }


    public function store(Request $request): RedirectResponse
    {
        $request['slug'] = str_replace(' ', '-', $request['title']);
        $request['start_date'] = date('Y-m-d h:i:s');
        $this->articleCategoryService->store($request);
        return to_route('article_category_list');
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
            $articleCategories = $this->articleCategoryService->showAllArticleCategory($request)->get();
            dd($articleCategories);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
            dd("Article Categories table not found!");
            return to_route('article_category_list');
        }
    }



    public function updateArticleCategory($id)
    {
        try {
            $data = array();
            $data['title'] = "Article Category 7";
            $data['slug'] = "article-category-7";
            $data['service_type'] = "ARTICLE";
            $data['description'] = "Test Description Test Description";
            $data['ref_id'] = "NULL";
            $data['created_by']  = '3ea2b260-2520-11ee-92ca-f01faf511254';
            $data['start_date'] = "2023-07-25 11:24:49";
            $data['created_at'] = "2023-07-23 11:24:49";
            $articleCategory = $this->articleCategoryService->updateArticleCategory($id, $data);
            dd($articleCategory);

        } catch (\Exception $e) {
            dd('Article Category data not found!');
            return Redirect::to('/article-category/list');
        }
    }



}
