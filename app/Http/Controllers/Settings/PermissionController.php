<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\InputField;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;
class PermissionController extends AbstractController
{
    public function __construct(private PermissionServiceInterface $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    public function getRepository()
    {
        return $this->permissionService;
    }

    public function configureActions(): iterable
    {

        return [
            ButtonField::init('new', 'new')->linkToRoute('permission_create')->createAsCrudBoardAction(),
            ButtonField::init(ButtonField::EDIT)->linkToRoute('permission_edit', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('permission_delete', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('permission_list')->createAsFormAction(),
        ];

    }

    public function configureForm()
    {
        $fields = [
            ChoiceField::init('applicationID', 'Select Application', choiceType: 'select', choiceList: $this->permissionService->getApplication())->setCssClass('my-class'),
            InputField::init('title'),
            InputField::init('shortDescription', 'Short Description', 'textarea'),
        ];

        $this->getForm($fields)
            ->setName('permission_form')
            ->setMethod('post')
            ->setActionUrl(route('permission_store'));
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

    public function permission()
    {
        $this->initGrid(['title', 'name', 'shortDescription'], pagination: 5);
        return view('home.settings.user.permission');
    }

    public function create()
    {
        $this->initCreate();
        return view('native::create');
    }

    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'applicationID' => 'required',
            'title' => 'required|string|min:6',
            'shortDescription' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors( $validator->errors())
                ->withInput();
        }
        $this->permissionService->savePermission($request);
        return redirect('permission');

    }
    public function edit(int $id)
    {
        $singlePermisison = $this->permissionService->getSinglePermission($id);
        return view('components.settings.user.single-permission')
        ->with('userApplicationIDs',$this->permissionService->getApplication())
        ->with('permission', $singlePermisison->data);

    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'applicationID' => 'required',
            'title' => 'required|string|min:6',
            'shortDescription' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors( $validator->errors())
                ->withInput();
        }
        $this->permissionService->updatePermission($request);
        return redirect('permission/edit/'. $request->get('id'));
    }

    public function delete(int $id)
    {
        $this->permissionService->deletePermission($id);
        return redirect('permission');
    }


}
