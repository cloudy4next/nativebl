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
use App\Contracts\TigerWeb\TagKeyRepositoryInterface;
use App\Models\TigerWeb\TagKey;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class TagKeyRepository  extends AbstractNativeRepository implements TagKeyRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return TagKey::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function tagKeyFilterData($filter)
    {
        //return $query = $this->all();
        $query = TagKey::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");

        }

        return $query;
    }


}
