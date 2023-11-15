<?php

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use App\Contracts\Services\Settings\RoleServiceInterface;
use App\Contracts\Services\Settings\UserServiceInterface;
use App\Traits\APITrait;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Field\FileField;
use NativeBL\Field\InputField;
use Illuminate\Http\Request;
use NativeBL\Field\TextField;
use Session;
use Auth;

class UserController extends AbstractController
{
    use APITrait;
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
        $authList = ['email_password' => 'Email & Password', 'bl_active_directory' => 'BL Active Directory'];

        $fields = [
            InputField::init('fullName')->setHtmlAttributes(['required' => true]),
            InputField::init('userName')->setHtmlAttributes(['required' => true]),
            InputField::init('emailAddress', 'Email', "email")->setHtmlAttributes(['required' => true]),
            InputField::init('mobileNumber')->validate('requried|numeric')->setHtmlAttributes(['required' => true]),
            InputField::init('password', 'Password', "password"),
            FileField::init('image'),
            // TO - Do
            InputField::init('roles')->setComponent('settings.user.role-component')->setLayoutClass('col-md-12')->setHtmlAttributes(['required' => true]),
            InputField::init('permissions')->setComponent('settings.user.permission-component')->setLayoutClass('col-md-12')->setHtmlAttributes(['required' => true]),
        ];

        switch ($this->getApplictionId()) {
            case $this->getApplictionId() != config('nativebl.base.toffee_analytics_application_id'):
                array_splice($fields, 4, 0, [ChoiceField::init('GrantType', 'Authenticaction Type', choiceType: 'select', choiceList: $authList)->setCssClass('my-class')]);
            default:
                array_splice($fields, 0, 0, [
                    ChoiceField::init(
                        'applicationID',
                        'Default Application ID',
                        choiceType: 'select',
                        choiceList: $applications,
                        selected: $this->userService->userApplicationID()
                    )
                ]);
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
            InputField::init('mobileNumber', 'Mobile Number', 'number'),
            TextField::init('emailAddress'),
        ];
        $this->getFilter($fields);
    }

    public function user()
    {
        $this->initGrid([
            Field::init('userName', 'User Name'),
            Field::init('fullName', 'Full Name'),
            Field::init('mobileNumber', 'Mobile Number'),
            Field::init('emailAddress', 'Email Address'),
            Field::init('isActive', 'Active Status')->formatValue(function ($value) {
                return $value == 1 ? "Active" : "Inactive";
            }),
            Field::init('isDeleted', 'Is Deleted')->formatValue(function ($value) {
                return $value == 1 ? "Yes" : "No";
            }),
        ], pagination: 5, config: [
            'headerRowCssClass' => 'thead-purple',
        ]);
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
        $rules = [
            'emailAddress' => 'required|email',
            'fullName' => 'required|string',
            'userName' => 'required|string',
            'mobileNumber' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:\+88|88|0)(1[3-9]\d{8})$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('Number is not a valid Bangladeshi mobile number.');
                    }
                },
            ],
            'GrantType' => 'string',
            'roles' => 'required|array',
            'permissions' => 'required|array',
        ];

        if ($request->get('GrantType') != 'bl_active_directory') {
            $rules['password'] = 'required|min:8';
        }

        $validator = \Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }

        $this->userService->saveUser($request);
        return to_route('user_list')->with('success', 'User Created Successfully');
    }
    public function delete($id)
    {
        $this->userService->deleteUser($id);
        return to_route('user_list')->with('success', 'User Deleted Successfully');
    }

    public function edit($id)
    {
        $singleUser = $this->userService->getSingleUser($id);
        $appliationName = $this->userService->getApplicationName($singleUser->data->applicationID);
        $userRole = $this->userService->dataParseFromArr((array) $singleUser->data, 'roles');
        $userPermission = $this->userService->dataParseFromArr((array) $singleUser->data, 'permissions');
        $authList = ['bl_active_directory' => 'BL Active Directory', 'email_password' => 'Email & Password'];
        $permissionFromCurrentApplication = $this->permissionServiceInterface->getAllPermission();
        $permissions = Session::get('applicationID') == config('nativebl.base.core_application_id') ? $permissionFromCurrentApplication : $this->getTotalListItem($this->getUserInformation('permissions'), $permissionFromCurrentApplication);
        return view('components.settings.user.edit-user')
            ->with('user', $singleUser->data)
            ->with('userRole', $userRole)
            ->with('appliationName', $appliationName)
            ->with('authList', $authList)
            ->with('userPermission', $userPermission)
            ->with('roles', $this->roleServiceInterface->getAllRole())
            ->with('permissions', $permissions);
    }

    public function update(Request $request)
    {

        $rules = [
            'emailAddress' => 'required|email',
            'fullName' => 'required|string',
            'userName' => 'required|string',
            'mobileNumber' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $pattern = '/^(?:\+88|88|0)(1[3-9]\d{8})$/';
                    if (!preg_match($pattern, $value)) {
                        $fail('Number is not a valid Bangladeshi mobile number.');
                    }
                },
            ],
            'roles' => 'required|array',
            'permissions' => 'required|array',
        ];

        if ($request->get('GrantType') != 'bl_active_directory') {
            $rules['password'] = 'required|min:8';
        }

        $validator = \Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }


        $this->userService->updateUser($request);
        return redirect('users')->with('success', 'User Updated Successfully');
    }
    public function passwordChange()
    {
        $singleUser = $this->userService->getSingleUser(Auth::user()->id);
        if ($singleUser->data->password == null) {
            return redirect('/')->with('warning', 'Please Change Your Windows Password To Reset');
        }
        return view('home.settings.user.user-password-reset');
    }
    public function passwordUpdate(Request $request)
    {
        $this->userService->userOldPasswordCheck($request->old_password);
        $this->userService->updateUserPassword($request);
        return redirect()->back()->with('success', 'Password Reset Succecssfully');
    }
}
