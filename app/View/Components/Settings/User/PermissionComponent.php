<?php

namespace App\View\Components\Settings\User;

use App\Contracts\Services\Settings\PermissionServiceInterface;
use App\Traits\APITrait;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Session;


class PermissionComponent extends Component
{
    use APITrait;
    /**
     * Create a new component instance.
     */

    public function __construct(private PermissionServiceInterface $permissionServiceInterface)
    {
        $this->permissionServiceInterface = $permissionServiceInterface;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $permissionFromCurrentApplication = $this->permissionServiceInterface->getAllPermission();
        $permissions = Session::get('applicationID') == config('nativebl.base.core_application_id') ? $permissionFromCurrentApplication : $this->getTotalListItem($this->getUserInformation('permissions'), $permissionFromCurrentApplication);
        return view('components.settings.user.permission-component')
            ->with('permissions', $permissions);
    }
}
