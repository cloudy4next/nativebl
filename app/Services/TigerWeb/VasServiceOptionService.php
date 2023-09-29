<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\VasServiceOptionServiceInterface;

use App\Repositories\TigerWeb\VasServiceOptionRepository;

final class VasServiceOptionService implements VasServiceOptionServiceInterface

{

	private $vasServiceOptionRepository;

	public function __construct(VasServiceOptionRepository $vasServiceOptionRepository)
    {

        $this->vasServiceOptionRepository = $vasServiceOptionRepository;
    }


    public function getAllVasServiceOption() : void
    {

    }


    public function showAllVasServiceOption($input)
    {
        return $this->vasServiceOptionRepository->vasServiceOptionFilterData($input);
    }

}
