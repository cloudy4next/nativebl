<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\ToffeAnalytics;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\ToffeAnalytics\CampaignRepositoryInterface;
use App\Models\ToffeAnalytics\ToffeeCampaign;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

use Auth;

class CampaignRepository extends AbstractNativeRepository implements CampaignRepositoryInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return ToffeeCampaign::class;
    }

    public function getGridData(array $filters = []): iterable
    {

        if (Auth::user()->isSuperAdmin()) {
            $query = ToffeeCampaign::join('toffee_agencies', 'toffee_campaigns.agency_id', '=', 'toffee_agencies.id')
                ->join('toffee_brands', 'toffee_campaigns.brand_id', '=', 'toffee_brands.id')
                ->get(['toffee_campaigns.*', 'toffee_brands.name as brandName', 'toffee_agencies.name as agencyName']);
        }
        else{
            return [];
        }

        return $query;
    }
    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if ($value !== null) {
                try{
                    $query->where($field, 'LIKE', '%' . $value . '%');

                }finally{
                    $query->where($field, '=', $value);
                }

            }
        }
        return $query;
    }

    public function store(Request $request)
    {
        if ($request['id'] != null) {
            $prevCampaign = $this->find($request['id']);
            $prevCampaign['campaign_name'] = $request['campaign_name'];
            $prevCampaign['campaign_id'] = $request['campaign_id'];
            $prevCampaign['agency_id'] = $request['agency_id'];
            $prevCampaign['brand_id'] = $request['brand_id'];
            $prevCampaign['created_by'] = Auth::user()->id;
            $prevCampaign->save();
        } else {

            return ToffeeCampaign::create($request->all());
        }
    }



    public function delete($id)
    {
        try {
            ToffeeCampaign::where('id', $id)->delete();
            return 1;

        } catch (\Exception $e) {
            return $e->getMessage();

        }
    }


}
