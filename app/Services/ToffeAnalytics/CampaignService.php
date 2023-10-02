<?php

namespace App\Services\ToffeAnalytics;

use App\Models\ToffeAnalytics\User;
use App\Contracts\Services\ToffeAnalytics\CampaignServiceInterface;

use App\Repositories\ToffeAnalytics\CampaignRepository;
use Illuminate\Http\Request;

final class CampaignService implements CampaignServiceInterface

{

	private $campaignRepository;

	public function __construct(CampaignRepository $campaignRepository)
    {

        $this->campaignRepository = $campaignRepository;
    }


    public function getAllCampaign() : void
    {

    }


    public function store(Request $request)
    {
        return $this->campaignRepository->store($request);
    }


    public function showAllCampaign($input)
    {
        return $this->campaignRepository->campaignFilterData($input);
    }

    public function delete($id)
    {
        return $this->campaignRepository->delete($id);
    }
}
