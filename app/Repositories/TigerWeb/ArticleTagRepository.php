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
use App\Contracts\TigerWeb\ArticleTagRepositoryInterface;
use App\Models\TigerWeb\ArticleTag;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class ArticleTagRepository  extends AbstractNativeRepository implements ArticleTagRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return ArticleTag::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function articleTagFilterData($filter)
    {
        //return $query = $this->all();
        $query = ArticleTag::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
           // $query->where('title', 'like', "%{$filter['search_text']}%");
           // $query->where('slug', 'like', "%{$filter['search_text']}%");

        }

        return $query;
    }


}
