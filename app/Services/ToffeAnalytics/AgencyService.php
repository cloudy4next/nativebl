<?php

namespace App\Services\ToffeAnalytics;

use App\Models\ToffeAnalytics\User;
use App\Contracts\Services\ToffeAnalytics\AgencyServiceInterface;

use App\Repositories\ToffeAnalytics\AgencyRepository;
use Illuminate\Http\Request;

final class AgencyService implements AgencyServiceInterface

{

	private $agencyRepository;

	public function __construct(AgencyRepository $agencyRepository)
    {

        $this->agencyRepository = $agencyRepository;
    }


    public function getAllAgency() : void
    {

    }


    public function store(Request $request)
    {
        return $this->agencyRepository->store($request);
    }

    public function deleteAgencyUser($id)
    {
        return $this->agencyRepository->deleteAgencyUser($id);
    }


    public function showAllAgency($input)
    {
        return $this->agencyRepository->agencyFilterData($input);
    }

    public function getAgencyDataById($id)
    {
        return $this->agencyRepository->getAgencyDataById($id);
    }

    public function delete($id)
    {
        return $this->agencyRepository->delete($id);
    }

}
