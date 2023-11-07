<?php

namespace App\View\Components\Settings\User;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Contracts\Services\Settings\RoleServiceInterface;

class RoleComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private RoleServiceInterface $roleServiceInterface)
    {
        $this->roleServiceInterface = $roleServiceInterface;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.settings.user.role-component')->with('roles', $this->roleServiceInterface->getAllRole());
    }
}
