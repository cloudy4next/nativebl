<?php

namespace App\Services\ToffeAnalytics;

use App\Models\ToffeAnalytics\User;
use App\Contracts\Services\ToffeAnalytics\BrandServiceInterface;

use App\Repositories\ToffeAnalytics\BrandRepository;
use Illuminate\Http\Request;

final class BrandService implements BrandServiceInterface

{

	private $brandRepository;

	public function __construct(BrandRepository $brandRepository)
    {

        $this->brandRepository = $brandRepository;
    }


    public function getAllBrand() : void
    {

    }


    public function store(Request $request)
    {
        return $this->brandRepository->store($request);
    }


    public function deleteBrandUser($id)
    {
        return $this->brandRepository->deleteBrandUser($id);
    }


    public function showAllBrand($input)
    {
        return $this->brandRepository->brandFilterData($input);
    }

    
    public function getBrandDataById($id)
    {
        return $this->brandRepository->getBrandDataById($id);
    }
    
    public function delete($id)
    {
        return $this->brandRepository->delete($id);
    }


}
