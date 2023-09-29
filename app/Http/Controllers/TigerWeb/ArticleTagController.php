<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleTagServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\ArticleTagRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\ArticleTagService;
use Illuminate\Support\Facades\Redirect;

class ArticleTagController extends AbstractController
{
    private ArticleTagServiceInterface $articleTagService;
    public function __construct(private ArticleTagRepositoryInterface $repo, ArticleTagServiceInterface $articleTagService)
    {
        $this->articleTagService = $articleTagService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('article_tag_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('article_tag_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_tag_detail'),
            ButtonField::init('new', 'new')->linkToRoute('article_tag_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('article_tag_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('article_tag_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('article_id'),
            TextField::init('tag_key_id'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('article_tag_form')
            ->setMethod('post')
            ->setActionUrl(route('article_tag_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function articleTags()
    {
        $this->initGrid(['article_id','tag_key_id','created_at'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['article_id','tag_key_id','created_at']);
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
        $this->initForm('article_tag_form', route('article_tag_save'), 'post', ['id' => 'myForm']);

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
        return to_route('article_tag_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            $articleTags = $this->articleTagService->showAllArticleTag($request)->get();
            dd($articleTags);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Article Tags table not found!");
            return Redirect::to('/article-tag/list');
        }
    }



}
