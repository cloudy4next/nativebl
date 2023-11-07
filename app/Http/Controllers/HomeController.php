<?php

namespace App\Http\Controllers;

use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\CustomerRepositoryInterface;
use NativeBL\Field\ButtonField;
use NativeBL\Field\DateTimeField;
use NativeBL\Field\TextareaField;
use NativeBL\Field\TextField;
use Illuminate\Http\RedirectResponse;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\FileField;
use NativeBL\Field\IdField;
use NativeBL\Field\InputField;
use NativeBL\Field\Field;

class HomeController extends AbstractController
{
    use HelperTrait;

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
            ButtonField::init(ButtonField::EDIT)->linkToRoute('customer_edit'),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('customer_delete'), //->displayIf(fn($row): bool => $row['id'] == 10 ),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('customer_detail', function ($row) {
                return ['id' => $row['id']];
            }),
            // ButtonField::init('custom1')->linkToRoute('customer_custom',['name'=>'full_name'])->addCssClass('btn-info')->setIcon('fa-square'),
            ButtonField::init('new', 'new')->linkToRoute('customer_create')->createAsCrudBoardAction()->iconForNew(),
            ButtonField::init('custom', 'Action')->linkToRoute('customer_list')->setIcon('fa-arrow-right')->setHtmlAttributes(['class' => 'btn-custom'])->createAsCrudBoardAction(),
            ButtonField::init('submit')->createAsFormSubmitAction()->setHtmlAttributes(['data-role' => 'update']),
            ButtonField::init('cancel')->linkToRoute('customer_list')->createAsFormAction(),
            ButtonField::init('back')->linkToRoute('customer_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            ButtonField::init('cancel')->linkToRoute('customer_list')->createAsShowAction()->setIcon('fa-arrow-left'),
            //ButtonField::init('other')->linkToRoute('other_link')->addCssClass('btn-secondary')->setIcon('fa-pencil')
        ];
    }

    public function configureForm(): void
    {
        $fields = [
            IdField::init('id'),
            TextField::init('full_name')->validate('required|max:255')->setLayoutClass('col-md-3'),
            TextField::init('msisdn')->setHtmlAttributes(['required' => true, 'maxlength' => 13, 'minlength' => 11]),
            // HiddenField::init('msisdn')->setDefaultValue('0000000111122'),
            //     ChoiceField::init('msisdn','MSISDN',choiceType:'checkbox', choiceList:[1=>'Super Admin','Admin','User','01962424629'=>'Custom'],
            //     empty:"-- Select Item --",selected:2
            //    )->setCssClass('mb-3'),
            // FileField::init('image'),
            //TextField::init('date_range')->setHtmlAttributes(['id'=>'daterangepicker']),
            // InputField::init('date_range','Date Range')->setAttribute('id','daterangepicker'),
            InputField::init('password', 'Password', "password"),
            InputField::init('email', 'Email', "email")->setReadonly(),
            DateTimeField::init('dob', 'DOB', "date"),
            TextareaField::init('remarks', 'Remarks', rows: 2)->validate('required|max:255|min:4')->setLayoutClass('col-md-12')
                ->setHtmlAttributes(['class' => 'editor']),
            // InputField::init('daterangepicker')->setComponent('custom.daterangepicker'),
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
        if ($this->SessionCheck('applicationID') == 4) {
            return redirect('/all-campaign');
        }
        return view('main');

    }

    public function customer()
    {
        // $this->initGrid(['full_name','msisdn'])
        $this->initGrid(
            [
                Field::init('full_name', 'Name')->setComponent('custom.grid.customer_full_name'),
                Field::init('msisdn', 'Mobile')->formatValue(fn($value): string => ltrim($value, '019'))
            ], pagination: 5);
        // return view('native::list');
        //  session()->flash('error','this is info');
        return view('list');
    }

    public function show($id)
    {
        $this->initShow($id, ['full_name', 'msisdn', 'Remarks']);
        return view('native::show');
    }

    public function edit($id)
    {
        $this->initEdit($id);
        return view('native::edit');
    }

    public function delete($id)
    {
        echo $id;
        exit();
    }

    public function create_old()
    {
        $this->initForm('customer_form', route('customer_save'), 'post', ['id' => 'myForm']);

        return view('create');
    }

    public function create()
    {
        $this->initCreate();
        return view('native::create');
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
        return to_route('customer_detail', ['id' => $data['id']]);
    }


    public function showDetails($id)
    {
        //$this->initDetails();
    }

    public function custom(string $name)
    {
        echo $name;
        exit();
    }


}
