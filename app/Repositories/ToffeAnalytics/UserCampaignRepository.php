<?php declare(strict_types=1);


namespace App\Repositories\ToffeAnalytics;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\ToffeAnalytics\UserCampaignRepositoryInterface;
use App\Models\ToffeAnalytics\UserCampaign;

use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;

use DB;

use Illuminate\Http\Request;

class UserCampaignRepository extends AbstractNativeRepository implements UserCampaignRepositoryInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return UserCampaign::class;
    }

    public function getGridData(): iterable
    {
        return UserCampaign::class;
    }



    public function articleFilterData($filter)
    {

    }



    public function store(Request $request)
    {

    }

}
