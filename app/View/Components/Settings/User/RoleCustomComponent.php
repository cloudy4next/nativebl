<?php

namespace App\View\Components\Settings\User;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Contracts\Services\Settings\MenuServiceInterface;

class RoleCustomComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private MenuServiceInterface $menuService )
    {
        $this->menuService = $menuService;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('components.settings.user.role-custom-component')
        ->with('menus', $this->menuService->getAllMenu());
    }
}
