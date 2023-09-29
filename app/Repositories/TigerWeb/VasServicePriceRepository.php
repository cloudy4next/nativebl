<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repositories\TigerWeb;


use NativeBL\Repository\AbstractNativeRepository;
use App\Contracts\TigerWeb\VasServicePriceRepositoryInterface;
use App\Models\TigerWeb\VasServicePrice;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class VasServicePriceRepository  extends AbstractNativeRepository implements VasServicePriceRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return VasServicePrice::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function vasServicePriceFilterData($filter)
    {
        //return $query = $this->all();
        $query = VasServicePrice::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('article_category_id', 'like', "%{$filter['search_text']}%");
            $query->orWhere('service_name', 'like', "%{$filter['search_text']}%");
            $query->orWhere('short_code', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
