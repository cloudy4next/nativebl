<?php

namespace App\View\Components\Settings\User;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Contracts\Services\Settings\MenuServiceInterface;
use App\Traits\APITrait;
use App\Traits\HelperTrait;
use Session;
class RoleCustomComponent extends Component
{
    use HelperTrait, APITrait;
    /**
     * Create a new component instance.
     */
    public function __construct(private MenuServiceInterface $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menuFromCurrentApplication = $this->menuService->getAllMenu();
        $menus = Session::get('applicationID') == config('nativebl.base.core_application_id') ? $menuFromCurrentApplication : $this->getTotalListItem($this->getUserInformation('menu'), $menuFromCurrentApplication);

        return view('components.settings.user.role-custom-component')
            ->with('menus', $menus);
    }
}
