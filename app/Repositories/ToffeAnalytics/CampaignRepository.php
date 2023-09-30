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
        $toffeeId = 2;
        // dd(Auth::user()->isSpecificAgency($toffeeId));
        if (Auth::user()->isSuperAdmin()) {
            $query = ToffeeCampaign::join('users', 'toffee_campaigns.user_id', '=', 'users.id')
                ->join('toffee_agencies', 'toffee_campaigns.agency_id', '=', 'toffee_agencies.id')
                ->join('toffee_brands', 'toffee_campaigns.brand_id', '=', 'toffee_brands.id')
                ->get(['toffee_campaigns.*', 'users.email as userId', 'toffee_brands.name as brandName', 'toffee_agencies.name as agencyName']);

        } else {
            if (Auth::user()->getToffeeAgencyId()) {
                $query = ToffeeCampaign::where('agency_id', Auth::user()->getToffeeAgencyId())
                    ->join('users', 'toffee_campaigns.user_id', '=', 'users.id')
                    ->join('toffee_agencies', 'toffee_campaigns.agency_id', '=', 'toffee_agencies.id')
                    ->join('toffee_brands', 'toffee_campaigns.brand_id', '=', 'toffee_brands.id')
                    ->get(['toffee_campaigns.*', 'users.email as userId', 'toffee_brands.name as brandName', 'toffee_agencies.name as agencyName']);
            } else {
                $query = ToffeeCampaign::whereIn('agency_id', function ($query) {
                    $query->select('agency_id')->from('toffee_agency_user_maps')->where('user_id', Auth::user()->id);
                })
                    ->orWhereIn('brand_id', function ($query) {
                        $query->select('brand_id')->from('toffee_brand_user_maps')->where('user_id', Auth::user()->id);
                    })
                    ->join('users', 'toffee_campaigns.user_id', '=', 'users.id')
                    ->join('toffee_agencies', 'toffee_campaigns.agency_id', '=', 'toffee_agencies.id')
                    ->join('toffee_brands', 'toffee_campaigns.brand_id', '=', 'toffee_brands.id')
                    ->get(['toffee_campaigns.*', 'users.email as userId', 'toffee_brands.name as brandName', 'toffee_agencies.name as agencyName']);
            }
            // dd($query);
        }

        return $query;
    }
    public function applyFilterData(Collection $data, array $filters): Collection
    {
        foreach ($filters as $field => $value) {
            $filtered = $data->where($field, $value);
            $data = $filtered;
        }
        return $data;
    }


    public function store(Request $request)
    {
        // dd($request);
        if ($request['id'] != null) {

            $prevCampaign = $this->find($request['id']);
            $prevCampaign['campaign_name'] = $request['campaign_name'];
            $prevCampaign['campaign_id'] = $request['campaign_id'];
            $prevCampaign['agency_id'] = $request['agency_id'];
            $prevCampaign['brand_id'] = $request['brand_id'];
            $prevCampaign['user_id'] = $request['user_id'];
            $prevCampaign['created_by'] = $request['created_by'];
            $prevCampaign->save();
        } else {

            return ToffeeCampaign::create($request->all());
        }
    }


    public function agencyFilterData($filter)
    {
        //return $query = $this->all();
        $query = ToffeAgency::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('name', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
