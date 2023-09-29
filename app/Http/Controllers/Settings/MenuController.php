<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\MenuServiceInterface;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\InputField;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;

class MenuController extends AbstractController
{

    private MenuServiceInterface $MenuService;
    public function __construct(MenuServiceInterface $MenuService)
    {
        $this->MenuService = $MenuService;
    }


    public function getRepository()
    {
        return $this->MenuService;
    }
    public function configureActions(): iterable
    {
        return [
            ButtonField::init('new', 'new')->linkToRoute('menu_create')->createAsCrudBoardAction(),
            ButtonField::init(ButtonField::EDIT)->linkToRoute('menu_edit', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init(ButtonField::DELETE)->linkToRoute('menu_delete', function ($row) {
                return ['id' => $row['id']];
            }),
            ButtonField::init('submit')->createAsFormSubmitAction(),
            ButtonField::init('cancel')->linkToRoute('menu_list')->createAsFormAction(),
        ];
    }

    public function configureForm()
    {
        $menus = $this->MenuService->keyPairParentID();
        $menus[-1] = 'None';
        $fields = [
            // InputField::init('applicationID', 'Application ID', 'number'),
            ChoiceField::init('applicationID', 'Select Application', choiceType: 'select', choiceList: $this->MenuService->getApplication())->setCssClass('my-class'),
            InputField::init('title'),
            InputField::init('iconName'),
            ChoiceField::init('parentID', 'Parent Select', choiceType: 'select', choiceList: $menus)->setCssClass('my-class'),
            InputField::init('displayOrder', 'Display Order', 'number'),
            InputField::init('target'),

        ];
        $this->getForm($fields)
            ->setName('menu_form')
            ->setMethod('post')
            ->setActionUrl(route('menu_store'));
    }

    public function configureFilter(): void
    {
        $fields = [
            TextField::init('title'),
            TextField::init('name'),
        ];
        $this->getFilter($fields);
    }

    public function menu()
    {
        $this->initGrid(['title', 'name', 'applicationID', 'parentID'], pagination: 5);
        return view('components.settings.user.menu');
    }

    public function create()
    {
        $this->initCreate();
        return view('native::create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'parentID' => 'required',
            'title' => 'required|string',
            'iconName' => 'required|string',
            'displayOrder' => 'required|integer',
            'target' => 'required|string',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->MenuService->saveMenu($request);
        return to_route('menu_list');
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'parentID' => 'required',
            'title' => 'required|string',
            'iconName' => 'required|string',
            'displayOrder' => 'required|integer',
            'target' => 'required|string',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->MenuService->saveMenu($request);
        return to_route('menu_list');
    }

    public function edit(int $id)
    {
        $validator = \Validator::make($request->all(), [
            'parentID' => 'required',
            'title' => 'required|string',
            'iconName' => 'required|string',
            'displayOrder' => 'required|integer',
            'target' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // dd($this->getRepository()->getAllMenu());
        $requestedMenu = $this->MenuService->singleMenu($id);
        return view('components.settings.user.single-menu')
            ->with('menu', $requestedMenu->data)
            ->with('userApplicationIDs', $this->MenuService->getApplication())
            ->with('all_menu', $this->getRepository()->getAllMenu());
    }

    public function delete($id)
    {
        $this->MenuService->deleteMenu($id);
        return to_route('menu_list');
    }
}
