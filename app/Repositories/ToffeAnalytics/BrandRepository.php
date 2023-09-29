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
use App\Contracts\ToffeAnalytics\BrandRepositoryInterface;
use App\Models\ToffeAnalytics\ToffeeBrand;
use App\Models\ToffeAnalytics\ToffeeBrandUserMap;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

use DB;

class BrandRepository  extends AbstractNativeRepository implements BrandRepositoryInterface
{

   public function getModelFqcn(): string
   {
     return ToffeeBrand::class;
   }

   public function getGridData(array $filters=[]) : ?iterable
   {
       return ToffeeBrand::select();
   }

   public function getGridQuery(): ?Builder
   {
      return ToffeeBrand::select();
   }

   public function getBrandDataById($id)
   {
       $query = ToffeeBrand::with('brandUserMap')
                ->where('id', $id)
                ->first();

          return $query;      
   }


     public function store(Request $request)
     {
      // dd($request);
      $destinationPath = public_path('toffy/img/');
      if($request['id'] != null){

        try {
         DB::beginTransaction();

        $prevBrand = $this->find($request['id']);
        $prevBrand['name'] = $request['name'];
        $prevBrand['icon'] = $request['icon'];
        $prevBrand['created_by'] = $request['created_by'];
        $prevBrand->save();

        ToffeeBrandUserMap::where('brand_id', $request['id'])->delete();

        foreach ($request['user'] as $userId){
            $brandUserMap = new ToffeeBrandUserMap();
            $brandUserMap->brand_id = $request['id'];
            $brandUserMap->user_id = $userId;
            $brandUserMap->created_by  = $request['created_by'];
            $brandUserMap->save();
        }

        DB::commit();
         return 1;

      }
       catch (\Exception $e) {
         DB::rollBack();
         return $e->getMessage();

      }
      }
      else{

        /*Uploading agency icon*/

        try {
         DB::beginTransaction();
      $icon_modify_name = '';
      if ($request->hasFile('icon')) {
         $icon = $request->file('icon');

         $extension = $icon->getClientOriginalExtension();
         $icon_file_name = 'brand_icon_' . strtotime("+1 second") . '.' . $extension;
         $upload_success_document = $icon->move($destinationPath, $icon_file_name);

         $icon_modify_name = 'toffy/img/'.$icon_file_name;
         $request['icon'] = $icon_modify_name;
        }
        

        $brandId = ToffeeBrand::create($request->all())->id;

        foreach ($request['user'] as $userId){
            $brandUserMap = new ToffeeBrandUserMap();
            $brandUserMap->brand_id = $brandId;
            $brandUserMap->user_id = $userId;
            $brandUserMap->created_by  = $request['created_by'];
            $brandUserMap->save();
        }

        DB::commit();
       return 1;

      }
       catch (\Exception $e) {
         DB::rollBack();
         return $e->getMessage();

      }
      }
      // return ToffeAgency::create($request->all());
     }



   public function deleteBrandUser($id)
    {
        return ToffeeBrandUserMap::where('id', $id)->delete() ? 1 : 0;
    }


   public function delete($id)
    {
      try {
         DB::beginTransaction();
        ToffeeBrandUserMap::where('brand_id', $id)->delete();
        ToffeeBrand::where('id', $id)->delete();

         DB::commit();
         return 1;

      }
       catch (\Exception $e) {
         DB::rollBack();
         return $e->getMessage();

      }
    }


   public function brandFilterData($filter)
    {
        //return $query = $this->all();
        $query = ToffeeBrand::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('name', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
