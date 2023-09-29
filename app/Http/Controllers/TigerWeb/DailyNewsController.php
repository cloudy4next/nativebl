<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\DailyNewsServiceInterface;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\DailyNewsRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\IdField;

use App\Services\TigerWeb\DailyNewsService;
use Illuminate\Support\Facades\Redirect;

class DailyNewsController extends AbstractController
{
    private DailyNewsServiceInterface $dailyNewsService;
    public function __construct(private DailyNewsRepositoryInterface $repo, DailyNewsServiceInterface $dailyNewsService)
    {
        $this->dailyNewsService = $dailyNewsService;
    }

    public function getRepository()
    {
        return $this->repo;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('daily_news_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('daily_news_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('daily_news_detail'),
            ButtonField::init('new', 'new')->linkToRoute('daily_news_create')->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('daily_news_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('daily_news_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }




    public function configureForm()
    {
        $fields = [
            IdField::init('id'),
            TextField::init('title')->validate('required|max:255'),
            TextField::init('slug'),
            TextField::init('content'),
            TextField::init('is_active'),
            TextField::init('display_order'),
            TextField::init('created_by'),
            TextField::init('start_date'),
            // TextareaField::init('my_remarks')
        ];
        $this->getForm($fields)
            ->setName('daily_news_form')
            ->setMethod('post')
            ->setActionUrl(route('daily_news_save'));
    }


    public function index(Request $request)
    {
        return view('base');
    }

    public function main(Request $request)
    {
        return view('main');

    }

    public function dailyNews()
    {
        $this->initGrid(['title','slug','content', 'is_active', 'display_order', 'created_by', 'updated_by', 'start_date', 'end_date'])
        ;
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['title','slug','content', 'is_active', 'display_order', 'created_by', 'updated_by', 'start_date', 'end_date']);
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
        $this->initForm('daily_news_form', route('daily_news_save'), 'post', ['id' => 'myForm']);

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
        return to_route('daily_news_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }


    public function listWithSearch(Request $request)
    {
        try {
            // $request['search_text'] = "Daily News 1";
            $dailyNews = $this->dailyNewsService->showAllDailyNews($request)->get();
            dd($dailyNews);
            //return view('articles.index', compact('articles', 'request'));
        } catch (\Exception $e) {
        	dd("Daily News table not found!");
            return Redirect::to('/daily-news/list');
        }
    }




}
