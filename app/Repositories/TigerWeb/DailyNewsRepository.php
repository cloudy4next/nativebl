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
use App\Contracts\TigerWeb\DailyNewsRepositoryInterface;
use App\Models\TigerWeb\DailyNews;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class DailyNewsRepository  extends AbstractNativeRepository implements DailyNewsRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return DailyNews::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function dailyNewsFilterData($filter)
    {
        //return $query = $this->all();
        $query = DailyNews::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
            $query->orWhere('content', 'like', "%{$filter['search_text']}%");

        }
        return $query;
    }


}
