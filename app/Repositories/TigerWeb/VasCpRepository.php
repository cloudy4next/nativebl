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
use App\Contracts\TigerWeb\VasCpRepositoryInterface;
use App\Models\TigerWeb\VasCp;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class VasCpRepository  extends AbstractNativeRepository implements VasCpRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return VasCp::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function vasCpFilterData($filter)
    {
        //return $query = $this->all();
        $query = VasCp::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('name', 'like', "%{$filter['search_text']}%");
            $query->orWhere('address', 'like', "%{$filter['search_text']}%");
            $query->orWhere('contact', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
