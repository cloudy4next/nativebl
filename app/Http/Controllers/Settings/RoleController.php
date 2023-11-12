<?php

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\MenuServiceInterface;
use Illuminate\Http\Request;
use App\Contracts\Services\Settings\RoleServiceInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Field\InputField;
use NativeBL\Field\TextField;

class RoleController extends AbstractController
{

    public function __construct(private RoleServiceInterface $roleService, private MenuServiceInterface $menuServiceInterface)
    {
        $this->roleService = $roleService;
    }
    public function getRepository()
    {
        return $this->roleService;
    }


    public function configureActions(): iterable
    {

        return [
            ButtonField::init('new', 'new')->linkToRoute('role_create')->createAsCrudBoardAction(),
            ButtonField::init(ButtonField::EDIT)->linkToRoute('role_edit', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('role_delete', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('role_list')->createAsFormAction(),
        ];

    }

    public function configureForm()
    {
        $fields = [
            ChoiceField::init('applicationID', 'Select Application', choiceType: 'select', choiceList: $this->roleService->getApplication())->setCssClass('my-class'),
            InputField::init('title')->setHtmlAttributes(['required'=>true,'minlength'=>3]),
            InputField::init('shortDescription', 'Short Description', 'textarea')->setHtmlAttributes(['required'=>true,'minlength'=>3]),
            InputField::init('menus')->setComponent('settings.user.role-custom-component')->setHtmlAttributes(['required'=>true,'minlength'=>8])->setLayoutClass('col-md-12'),

        ];
        $this->getForm($fields)
            ->setName('role_form')
            ->setMethod('post')
            ->setActionUrl(route('role_store'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('title'),
            TextField::init('name'),
            TextField::init('shortDescription'),
        ];
        $this->getFilter($fields);
    }

    public function role()
    {
        $this->initGrid([
            'title',
            'name',
            Field::init('shortDescription', 'Short Description'),
            Field::init('isActive', 'Active Status')->formatValue(function($value) {
                return $value== 1 ? "Active" : "Inactive";
            }),
            Field::init('isDeleted', 'Is Deleted')->formatValue(function($value) {
                return $value== 1 ? "Yes" : "No";
            }),
        ], pagination: 5);
        return view('home.settings.role.list');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'applicationID' => 'required',
            'title' => 'required|string|min:6',
            'shortDescription' => 'required|string|min:6',
            'menus' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->roleService->saveRole($request);
        return to_route('role_list')->with('success', 'Role Created Successfully');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.settings.role.create');
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'applicationID' => 'required',
            'title' => 'required|string|min:6',
            'shortDescription' => 'required|string|min:6',
            'menus' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $roleID = $request->get('id');
        $this->roleService->updateRole($request);
        return redirect('role/edit/' . $roleID)->with('success', 'Role Updated Successfully');
    }

    public function delete(int $id)
    {
        $this->roleService->deleteRole($id);
        return redirect('role')->with('success', 'Role Deleted Successfully');
    }

    public function edit($id)
    {
        $singleRole = $this->roleService->getSingleRole($id);
        return view('components.settings.user.single-role')->with('role', $singleRole->data)
            ->with('userApplicationIDs', $this->roleService->getApplication())
            ->with('menus', $this->menuServiceInterface->getAllMenu());
    }

    public function show($id)
    {

    }

}
