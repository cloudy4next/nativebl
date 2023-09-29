<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\VasServicePriceServiceInterface;

use App\Repositories\TigerWeb\VasServicePriceRepository;

final class VasServicePriceService implements VasServicePriceServiceInterface

{

	private $vasServicePriceRepository;

	public function __construct(VasServicePriceRepository $vasServicePriceRepository)
    {

        $this->vasServicePriceRepository = $vasServicePriceRepository;
    }


    public function getAllVasServicePrice() : void
    {

    }


    public function showAllVasServicePrice($input)
    {
        return $this->vasServicePriceRepository->vasServicePriceFilterData($input);
    }

}
