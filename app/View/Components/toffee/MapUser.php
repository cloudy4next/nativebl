<?php

namespace App\View\Components\toffee;

use App\Contracts\Services\Settings\UserServiceInterface;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MapUser extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(private UserServiceInterface $userServiceInterface)
    {
        $this->userServiceInterface = $userServiceInterface;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $userList = $this->userServiceInterface->getAllUserIDNameArr();

        return view('components.toffee.map-user')->with('userList', $userList);
    }
}
