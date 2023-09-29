<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\VasCpServiceInterface;

use App\Repositories\TigerWeb\VasCpRepository;

final class VasCpService implements VasCpServiceInterface

{

	private $vasCpRepository;

	public function __construct(VasCpRepository $vasCpRepository)
    {

        $this->vasCpRepository = $vasCpRepository;
    }


    public function getAllVasCp() : void
    {

    }


    public function showAllVasCp($input)
    {
        return $this->vasCpRepository->vasCpFilterData($input);
    }

}
