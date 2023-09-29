<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\CustomerRepositoryInterface;
use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Support\Facades\CrudBoardFacade;
use NativeBL\Field\ButtonField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\DateTimeField;
use NativeBL\Field\FileField;
use NativeBL\Field\IdField;
use NativeBL\Field\InputField;
use NativeBL\Field\Field;
use NativeBL\Field\HiddenField;
use NativeBL\Services\CrudBoard\GridFilter;

class HomeController extends AbstractController
{
    public function __construct(private CustomerRepositoryInterface $repo)
    {
    }

    public function getRepository()
    {
        return $this->repo;
    }

    public function configureActions(): iterable
    {
        return [
            ButtonField::init(ButtonField::EDIT)->linkToRoute('customer_edit')->addCssClass('fa-file-lines'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('customer_delete'),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('customer_detail',function($row){ return ['id'=>$row['id']]; }),
            ButtonField::init('custom')->linkToRoute('customer_custom',['name'=>'msisdn'])->addCssClass('btn-danger')->setIcon('fa-square'),
            ButtonField::init('new','new')->linkToRoute('customer_create')->createAsCrudBoardAction()->iconForNew(),
            ButtonField::init('custom','Custom')->linkToRoute('customer_list')->createAsCrudBoardAction()->setIcon('fa-arrow-right'),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('customer_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('customer_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            ButtonField::init('cancel')->linkToRoute('customer_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
          ];
    }




    public function configureForm() : void
    {
        $fields =  [
            IdField::init('id'),
            TextField::init('full_name')->validate('required|max:255'),
            TextField::init('msisdn'),
           // HiddenField::init('msisdn')->setDefaultValue('0000000111122'),
            ChoiceField::init('type','User Type',choiceType:'checkbox', choiceList:[1=>'Super Admin','Admin','User'],
            empty:"-- Select Item --", selected:2
           )->setCssClass('my-class'),
            FileField::init('image'),
            DateTimeField::init('start_date'),
            InputField::init('password','Password', "password"),
            InputField::init('email','Email', "email")->setComponent('custom.email'),
            InputField::init('dob','DOB', "date"),
            TextareaField::init('remarks','Remarks',rows:4)->validate('required|max:255|min:4'),
            InputField::init('daterangepicker')->setComponent('custom.daterangepicker'),
            ];
        $this->getForm($fields)
        ->setName('customer_form')
        ->setMethod('post')
        ->setActionUrl(route('customer_save'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('full_name'),
            TextField::init('msisdn'),
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

    public function customer()
    {
       // $this->initGrid(['full_name','msisdn'])
        $this->initGrid([Field::init('full_name','Name'),Field::init('msisdn','Mobile')], pagination: 5)
        ;
        return view('native::list');
    }

    public function show($id)
    {
        $this->initShow($id,['full_name','msisdn','Remarks']);
        return view('native::show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('native::edit');
    }
    public function delete($id)
    {
        echo $id; exit();
    }
    public function create_old()
    {
       $this->initForm('customer_form',route('customer_save'),'post', ['id'=>'myForm']);

       return view('create');
    }

   public function create()
   {
      $this->initCreate();
      return view('native::create');
   }


    public function store(Request $request): RedirectResponse
    {
        // dd($request);
        $form = $this->initStore($request);
        //print_r($request->all()); exit();
        // $validated = $request->validate([
        //     'full_name' => 'required|max:255',
        //     'msisdn' => 'required',
        // ]);
        $data = $form->getData();
        return to_route('customer_detail', ['id' =>$data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }

    public function custom(string $name)
    {
       echo $name; exit();
    }








}
