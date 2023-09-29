<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\TagKeyServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\TagKeyRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\TagKeyService;
use Illuminate\Support\Facades\Redirect;

class TagKeyController extends AbstractController
{
    private TagKeyServiceInterface $tagKeyService;
    public function __construct(private TagKeyRepositoryInterface $repo, TagKeyServiceInterface $tagKeyService)
    {
        $this->tagKeyService = $tagKeyService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('tag_key_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('tag_key_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('tag_key_detail'),
            ButtonField::init('new', 'new')->linkToRoute('tag_key_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('tag_key_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('tag_key_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('title')->validate('required|max:255'),
            TextField::init('slug'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('tag_key_form')
            ->setMethod('post')
            ->setActionUrl(route('tag_key_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function tagKeys()
    {
        $this->initGrid(['title','slug','created_at'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['title','slug','created_at']);
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
        $this->initForm('tag_key_form', route('tag_key_save'), 'post', ['id' => 'myForm']);

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
        return to_route('tag_key_detail', ['id' => $data['id']]);
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
            $tagKeys = $this->tagKeyService->showAllTagKey($request)->get();
            dd($tagKeys);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Tag Keys table not found!");
            return Redirect::to('/tag-key/list');
        }
    }



}
