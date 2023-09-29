<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\CampaignServiceInterface;

use App\Repositories\TigerWeb\CampaignRepository;
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

}
