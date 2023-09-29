<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\VasServiceServiceInterface;

use App\Repositories\TigerWeb\VasServiceRepository;

final class VasServiceService implements VasServiceServiceInterface

{

	private $vasServiceRepository;

	public function __construct(VasServiceRepository $vasServiceRepository)
    {

        $this->vasServiceRepository = $vasServiceRepository;
    }


    public function getAllVasService() : void
    {

    }


    public function showAllVasService($input)
    {
        return $this->vasServiceRepository->vasServiceFilterData($input);
    }

}
