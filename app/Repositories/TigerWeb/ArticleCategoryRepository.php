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
use App\Contracts\TigerWeb\ArticleCategoryRepositoryInterface;
use App\Models\TigerWeb\ArticleCategory;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;

use Illuminate\Http\Request;


class ArticleCategoryRepository  extends AbstractNativeRepository implements ArticleCategoryRepositoryInterface, CrudGridLoaderInterface
{
   public function getModelFqcn(): string
   {
     return ArticleCategory::class;
   }

   public function getGridData() : iterable
   {
      // $with = ['parentCategory', 'createdBy', 'updatedBy'];
    // $query = ArticleCategory::query();
      $query = ArticleCategory::join('users', 'article_categories.created_by', '=', 'users.id')
                ->leftJoin('article_categories as ac', 'article_categories.ref_id', '=', 'ac.id')
                ->leftJoin('users as u', 'article_categories.updated_by', '=', 'u.id')
                ->get(['article_categories.*', 'users.email as createdBy', 'u.email as updatedBy', 'ac.title as parentCategory']);
        // if (isset($filter['search_text']) && $filter['search_text']) {
        //     $query->where('title', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('service_type', 'like', "%{$filter['search_text']}%");
        //     $query->orWhere('description', 'like', "%{$filter['search_text']}%");
        // }

      // $query->with($with);
      // dd($query->get());
                // dd($query);
        return $query;
   }


  public function store(Request $request)
   {
    // dd($request);
    if($request['id'] != null){

      $prevCategory = $this->find($request['id']);
      $prevCategory['end_date'] = date('Y-m-d h:i:s');
      $prevCategory['updated_by'] = $request['created_by'];
      $prevCategory->save();

      $request['ref_id'] = $request['id'];
      $request['id'] = NULL;      
      return ArticleCategory::create($request->all());
    }
    else{
      return ArticleCategory::create($request->all());
    }
   }



   public function articleCategoryFilterData($filter)
    {
        //return $query = $this->all();
        $query = ArticleCategory::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
            $query->orWhere('service_type', 'like', "%{$filter['search_text']}%");
            $query->orWhere('description', 'like', "%{$filter['search_text']}%");

        }

        return $query;
    }



   public function updateArticleCategory($id, $data)
    {
        // dd($articleCategory);

       // $articleReview = ArticleCategory::create($data);
        $articleCategoryNew = new ArticleCategory();
        $articleCategoryNew->title = $data['title'];
        $articleCategoryNew->slug = $data['slug'];
        $articleCategoryNew->service_type = $data['service_type'];
        $articleCategoryNew->description = $data['description'];
        $articleCategoryNew->ref_id = $id;
        $articleCategoryNew->created_by  = $data['created_by'];
        $articleCategoryNew->start_date = $data['start_date'];
        $articleCategoryNew->created_at = $data['created_at'];
        $articleCategoryNew = $articleCategoryNew->save();
        return $articleCategoryNew;
    }


}
