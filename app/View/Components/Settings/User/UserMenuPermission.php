<?php

namespace App\View\Components\Settings\User;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use App\Contracts\Services\Settings\RoleServiceInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserMenuPermission extends Component
{
    /**
     * Create a new component instance.
     */

    public function __construct(private RoleServiceInterface $roleService, private PermissionServiceInterface $permissionServiceInterface)
    {
        $this->roleService = $roleService;
        $this->permissionServiceInterface = $permissionServiceInterface;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.settings.user.user-menu-permission')
            ->with('roles', $this->roleService->getAllRole())
            ->with('permissions', $this->permissionServiceInterface->getAllPermission());
    }
}
