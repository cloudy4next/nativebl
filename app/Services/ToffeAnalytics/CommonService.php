<?php

namespace App\Services\ToffeAnalytics;


use App\Repositories\ToffeAnalytics\CommonRepository;

final class CommonService

{

	private $commonRepository;

	public function __construct(CommonRepository $commonRepository)
    {

        $this->commonRepository = $commonRepository;
    }

    public function toffeeAgencyList()
    {
        return $this->commonRepository->toffeeAgencyList();
    }

    public function toffeeBrandList()
    {
        return $this->commonRepository->toffeeBrandList();
    }
}
