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
use App\Contracts\TigerWeb\CampaignRepositoryInterface;
use App\Models\TigerWeb\Campaign;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;


use Illuminate\Http\Request;


class CampaignRepository  extends AbstractNativeRepository implements CampaignRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return Campaign::class;
   }

   public function getGridData() : iterable
   {
       $query = Campaign::join('users', 'campaigns.created_by', '=', 'users.id')
                ->join('articles', 'campaigns.article_id', '=', 'articles.id')
                ->leftJoin('users as u', 'campaigns.updated_by', '=', 'u.id')
                ->leftJoin('campaigns as c', 'campaigns.ref_id', '=', 'c.id')
                ->get(['campaigns.*', 'users.email as createdBy', 'u.email as updatedBy', 'articles.title as articleTitle', 'c.title as refCampaign']);
       return $query;
   }



   public function store(Request $request)
   {
    // dd($request);
    if($request['id'] != null){

        $prevData = $this->find($request['id']);
        $prevData->end_date = date('Y-m-d h:i:s');
        $prevData->updated_by = $request['created_by'];
        $prevData->save();

        $request['ref_id'] = $request['id'];
        $request['id'] = NULL;
        return Campaign::create($request->all());
    }
    else{
      return Campaign::create($request->all());
    }
   }


   public function campaignFilterData($filter)
    {
        //return $query = $this->all();
        $query = Campaign::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");

        }

        if (isset($filter['article_id']) && $filter['article_id']) {
            $query->where('article_id', '=', $filter['article_id']);
        }

        return $query;
    }


}
