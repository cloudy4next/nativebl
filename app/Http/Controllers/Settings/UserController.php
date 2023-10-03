<?php

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use App\Contracts\Services\Settings\RoleServiceInterface;
use App\Contracts\Services\Settings\UserServiceInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Field\InputField;
use Illuminate\Http\Request;
use NativeBL\Field\TextField;

class UserController extends AbstractController
{
    public function __construct(
        private UserServiceInterface $userService,
        private RoleServiceInterface $roleServiceInterface,
        private PermissionServiceInterface $permissionServiceInterface
    ) {
        $this->userService = $userService;
        $this->permissionServiceInterface = $permissionServiceInterface;
        $this->roleServiceInterface = $roleServiceInterface;
    }
    public function getRepository()
    {
        return $this->userService;
    }


    public function configureActions(): iterable
    {
        return [
            ButtonField::init('new', 'new')->linkToRoute('user_create')->createAsCrudBoardAction(),
            ButtonField::init(ButtonField::DETAIL)->linkToRoute('user_detail', function ($row) {
                return ['id' => $row['userID']];
            }),
            ButtonField::init(ButtonField::EDIT)->linkToRoute('user_edit', function ($row) {
                return ['id' => $row['userID']];
            }),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('user_delete', function ($row) {
                return ['id' => $row['userID']];
            }),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('user_list')->createAsFormAction(),
        ];
    }


    public function configureForm()
    {
        $applications = $this->userService->getApplication();
        $authList = ['bl_active_directory' => 'BL Active Directory', 'email_password' => 'Email & Password'];

        $fields = [
            ChoiceField::init(
                'applicationID',
                'Default Application ID',
                choiceType: 'select',
                choiceList: $applications,
                selected: $this->userService->userApplicationID()
            )->setDisabled(),
            InputField::init('fullName')->setHtmlAttributes(['required' => true, 'maxlength' => 50, 'minlength' => 6]),
            InputField::init('userName')->setHtmlAttributes(['required' => true, 'maxlength' => 50, 'minlength' => 6]),
            InputField::init('emailAddress', 'Email', "email")->setHtmlAttributes(['required' => true, 'maxlength' => 50, 'minlength' => 6]),
            InputField::init('mobileNumber')->validate('requried|numeric')->setHtmlAttributes(['required' => true, 'maxlength' => 15, 'minlength' => 11]),
            InputField::init('password', 'Password', "password")->validate('requried|numeric|min:8')->setLayoutClass('col-md-12')->setHtmlAttributes(['required' => true]),
            // TO - Do
            InputField::init('roles')->setComponent('settings.user.user-menu-permission')->setLayoutClass('col-md-12')->setHtmlAttributes(['required' => true]),
        ];
        if ($this->userService->checkIfToffee() == false) {
            array_splice($fields, 4, 0, [ChoiceField::init('GrantType', 'Authenticaction Type', choiceType: 'select', choiceList: $authList)->setCssClass('my-class')]);
        }
        $this->getForm($fields)
            ->setName('user_form')
            ->setMethod('post')
            ->setActionUrl(route('user_store'));
    }

    public function configureFilter(): void
    {
        $fields = [
            // TextField::init('userID'),
            TextField::init('userName'),
            TextField::init('fullName'),
            TextField::init('mobileNumber'),
            TextField::init('emailAddress'),
        ];
        $this->getFilter($fields);
    }

    public function user()
    {
        $this->initGrid([
            Field::init('userName','User Name'),
            Field::init('fullName','Full Name'),
            Field::init('mobileNumber','Mobile Number'),
            Field::init('emailAddress','Email Address'),
        ], pagination: 5); //'userID',
        return view('components.settings.user.user');
    }


    public function create()
    {
        $this->initCreate();
        return view('home.settings.user.create');

    }

    public function show($id)
    {
        $singleUser = $this->userService->getSingleUser($id);
        return view('components.settings.user.single-user')->with('data', $singleUser->data);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'emailAddress' => 'required|email',
            'fullName' => 'required|string',
            'password' => 'required|string|min:6',
            'mobileNumber' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:\+880|880)?(01[3-9]\d{8})$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('Number is not a valid Bangladeshi mobile number.');
                    }
                },
            ],
            'GrantType' => 'string',
            'roles' => 'required|array',
            'applicationID' => 'required|integer',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $this->userService->saveUser($request);
        return to_route('user_list');
    }
    public function delete($id)
    {
        $this->userService->deleteUser($id);
        return to_route('user_list');
    }

    public function edit($id)
    {
        $singleUser = $this->userService->getSingleUser($id);
        $appliationName = $this->userService->getApplicationName($singleUser->data->applicationID);
        $userRole = $this->userService->dataParseFromArr((array) $singleUser->data, 'roles');
        $userPermission = $this->userService->dataParseFromArr((array) $singleUser->data, 'permissions');
        $authList = ['bl_active_directory' => 'BL Active Directory', 'email_password' => 'Email & Password'];

        return view('components.settings.user.edit-user')
            ->with('user', $singleUser->data)
            ->with('userRole', $userRole)
            ->with('appliationName', $appliationName)
            ->with('authList', $authList)
            ->with('userPermission', $userPermission)
            ->with('roles', $this->roleServiceInterface->getAllRole())
            ->with('permissions', $this->permissionServiceInterface->getAllPermission());
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'emailAddress' => 'required|email',
            'fullName' => 'required|string',
            'password' => 'required|string|min:6',
            'mobileNumber' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:\+880|880)?(01[3-9]\d{8})$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('Number is not a valid Bangladeshi mobile number.');
                    }
                },
            ],
            'GrantType' => 'string',
            'roles' => 'required|array',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $id = $request->get('id');
        $this->userService->updateUser($request);
        return redirect('users/edit/' . $id);
    }
    public function passwordChange()
    {
        return view('home.settings.user.user-password-reset');
    }
    public function passwordUpdate(Request $request)
    {
        $this->userService->userOldPasswordCheck($request->old_password);
        $this->userService->updateUserPassword($request);
        return redirect('/');
    }

}
