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
use App\Contracts\ToffeAnalytics\AgencyRepositoryInterface;
use App\Models\ToffeAnalytics\ToffeeAgency;
use App\Models\ToffeAnalytics\ToffeeAgencyUserMap;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

use DB;

class AgencyRepository extends AbstractNativeRepository implements AgencyRepositoryInterface
{

    public function getModelFqcn(): string
    {
        return ToffeeAgency::class;
    }

    public function getGridData(array $filters = []): iterable
    {
        return ToffeeAgency::select();
    }

    public function applyFilterQuery(Builder $query, array $filters): Builder
    {
        if ($filters == null) {
            return $query;
        }
        $query->where('name','LIKE',"%{$filters['name']}%");

        return $query;

    }

    public function getGridQuery(): ?Builder
    {
        return ToffeeAgency::select();
    }

   public function getAgencyDataById($id)
   {
       $query = ToffeeAgency::with('agencyUserMap')
                ->where('id', $id)
                ->first();

        return $query;
    }


    public function store(Request $request)
    {
        $destinationPath = public_path('toffy/img/');
        if ($request['id'] != null) {

            try {
                DB::beginTransaction();

                $prevAgency = $this->find($request['id']);
                $prevAgency['name'] = $request['name'];
                $prevAgency['icon'] = $request['icon'];
                $prevAgency['created_by'] = $request['created_by'];
                $prevAgency->save();

                ToffeeAgencyUserMap::where('agency_id', $request['id'])->delete();

                foreach ($request['user'] as $userId) {
                    $agencyUserMap = new ToffeeAgencyUserMap();
                    $agencyUserMap->agency_id = $request['id'];
                    $agencyUserMap->user_id = $userId;
                    $agencyUserMap->created_by = $request['created_by'];
                    $agencyUserMap->save();
                }

                DB::commit();
                return 1;

            } catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();

            }
        } else {

            /*Uploading agency icon*/
            try {
                DB::beginTransaction();
                $icon_modify_name = '';
                if ($request->hasFile('icon')) {
                    $icon = $request->file('icon');

                    $extension = $icon->getClientOriginalExtension();
                    $icon_file_name = 'agency_icon_' . strtotime("+1 second") . '.' . $extension;
                    $upload_success_document = $icon->move($destinationPath, $icon_file_name);

                    $icon_modify_name = 'toffy/img/' . $icon_file_name;
                    $request['icon'] = $icon_modify_name;
                }


                $agencyId = ToffeeAgency::create($request->all())->id;

                foreach ($request['user'] as $userId) {
                    $agencyUserMap = new ToffeeAgencyUserMap();
                    $agencyUserMap->agency_id = $agencyId;
                    $agencyUserMap->user_id = $userId;
                    $agencyUserMap->created_by = $request['created_by'];
                    $agencyUserMap->save();
                }

                DB::commit();
                return 1;

            } catch (\Exception $e) {
                DB::rollBack();
                return $e->getMessage();

            }

        }
    }


    public function deleteAgencyUser($id)
    {
        return ToffeeAgencyUserMap::where('id', $id)->delete() ? 1 : 0;
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            ToffeeAgencyUserMap::where('agency_id', $id)->delete();
            ToffeeAgency::where('id', $id)->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();

        }
    }


    public function agencyFilterData($filter)
    {
        //return $query = $this->all();
        $query = ToffeeAgency::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('name', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
