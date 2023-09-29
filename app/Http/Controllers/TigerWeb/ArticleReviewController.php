<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleReviewServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\ArticleReviewRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\ArticleReviewService;
use Illuminate\Support\Facades\Redirect;

class ArticleReviewController extends AbstractController
{
    private ArticleReviewServiceInterface $articleReviewService;
    public function __construct(private ArticleReviewRepositoryInterface $repo, ArticleReviewServiceInterface $articleReviewService)
    {
        $this->articleReviewService = $articleReviewService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('article_review_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('article_review_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('article_review_detail'),
            ButtonField::init('new', 'new')->linkToRoute('article_review_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('article_review_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('article_review_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('article_id'),
            TextField::init('review_status'),
            TextField::init('review_comments'),
            TextField::init('created_at'),
            TextField::init('created_by'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('article_review_form')
            ->setMethod('post')
            ->setActionUrl(route('article_review_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function articleReviews()
    {
        $this->initGrid(['article_id','review_status','review_comments','created_at','created_by'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['article_id','review_status','review_comments','created_at','created_by']);
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
        $this->initForm('article_review_form', route('article_review_save'), 'post', ['id' => 'myForm']);

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
        return to_route('article_review_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            $articleReviews = $this->articleReviewService->showAllArticleReview($request)->get();
            dd($articleReviews);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Article Reviews table not found!");
            return Redirect::to('/article-review/list');
        }
    }




    public function raiseApproveTicket($data)
    {
        try {
            $data = array();
            $data['article_id'] = 1;
            $data['review_status'] = "APPROVED";
            $data['review_comments'] = "Review Comments Review Comments Review Comments Review Comments Review Comments approved";
            $data['created_by'] = '3ea2b260-2520-11ee-92ca-f01faf511254';
            $articleReview = $this->articleReviewService->raiseApproveTicket($data);
            dd($articleReview);
            
        } catch (\Exception $e) {
            dd('Article data not found!');
            return Redirect::to('/article-review/list');
        }
    }



}
