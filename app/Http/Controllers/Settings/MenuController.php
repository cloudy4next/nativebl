<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Contracts\Services\Settings\MenuServiceInterface;
use App\Traits\HelperTrait;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use NativeBL\Field\ButtonField;
use NativeBL\Field\ChoiceField;
use NativeBL\Field\Field;
use NativeBL\Field\InputField;
use NativeBL\Field\TextField;
use Illuminate\Http\Request;

class MenuController extends AbstractController
{
    use HelperTrait;
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

            ChoiceField::init('applicationID', 'Select Application', choiceType: 'select', choiceList: $this->MenuService->getApplication()),
            InputField::init('title')->setHtmlAttributes(['required' => true]),
            // InputField::init('iconName')->setHtmlAttributes(['required' => true]),
            ChoiceField::init('parentID', 'Parent Select', choiceType: 'select', choiceList: $menus)->setHtmlAttributes(['required' => true]),
            // InputField::init('displayOrder', 'Display Order', 'number')->setHtmlAttributes(['required' => true]),
            // InputField::init('target')->setHtmlAttributes(['required' => true]),

        ];
        // 4 is toffee here
        if ($this->checkifExitsApplication(4) == false) {
            array_splice($fields, 2, 0, [InputField::init('name')->setHtmlAttributes(['required' => true])]);
        }

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
        $this->initGrid([
            'title',
            'name',
            'applicationID',
            'parentID',
            Field::init('isActive', 'Active Status')->formatValue(function ($value) {
                return $value == 1 ? "Active" : "Inactive";
            }),
            Field::init('isDeleted', 'Is Deleted')->formatValue(function ($value) {
                return $value == 1 ? "Yes" : "No";
            }),
        ], pagination: 5, config: [
            'headerRowCssClass' => 'thead-purple',
        ]);
        return view('home.settings.menu.list');
    }

    public function create()
    {
        $this->initCreate();
        return view('home.settings.menu.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'parentID' => 'required',
            'title' => 'required|string',
            //'iconName' => 'required|string',
            //'displayOrder' => 'required|integer',
            //'target' => 'required|string',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->MenuService->saveMenu($request);
        return to_route('menu_list')->with('success', 'Menu Created Successfully');
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'parentID' => 'required',
            'title' => 'required|string',
            //'iconName' => 'required|string',
            //'displayOrder' => 'required|integer',
            //'target' => 'required|string',

        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator->errors())
                ->withInput();
        }
        $this->MenuService->saveMenu($request);
        return to_route('menu_list')->with('success', 'Menu Updated Successfully');
    }

    public function edit(int $id,)
    {
        $requestedMenu = $this->MenuService->singleMenu($id);
        return view('components.settings.user.single-menu')
            ->with('menu', $requestedMenu->data)
            ->with('userApplicationIDs', $this->MenuService->getApplication())
            ->with('all_menu', $this->getRepository()->getAllMenu());
    }

    public function delete($id)
    {
        $this->MenuService->deleteMenu($id);
        return to_route('menu_list')->with('success', 'Menu Deleted Successfully');
    }
}
