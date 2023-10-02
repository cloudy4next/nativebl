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
use App\Contracts\TigerWeb\ArticleRepositoryInterface;
use App\Models\TigerWeb\Article;
use App\Models\TigerWeb\TagKey;
use App\Models\TigerWeb\ArticleTag;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;

use DB;

use Illuminate\Http\Request;

class ArticleRepository  extends AbstractNativeRepository implements ArticleRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return Article::class;
   }

   public function getGridData(array $filters = []) : iterable
   {
       $query = Article::join('users', 'articles.created_by', '=', 'users.id')
                ->join('article_categories', 'articles.article_category_id', '=', 'article_categories.id')
                ->leftJoin('users as u', 'articles.updated_by', '=', 'u.id')
                ->leftJoin('articles as ac', 'articles.ref_id', '=', 'ac.id')
                ->get(['articles.*', 'users.email as createdBy', 'u.email as updatedBy', 'article_categories.title as articleCategoryTitle', 'ac.title as parentArticle']);
        return $query;
   }

   public function details($id)
   {
         // $articleDetails =  Article::with(['articleCategory', 'articleReview', 'articleTag.tagKey','parentArticle'])
         //  ->where('id', $id)
         //  ->get();
          $articleDetails =  Article::with(['articleCategory', 'articleReview', 'articleTag.tagKey','parentArticle', 'parentTree', 'articleFaq'])->where('id', $id)->get();
        // dd($articleDetails);
        return $articleDetails;
   }


   public function articleFilterData($filter)
    {
        //return $query = $this->all();
      //$filter['search_text'] = "Article 1";
      // $filter['article_category_id'] = 1;
        $query = Article::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
            $query->where('title', 'like', "%{$filter['search_text']}%");
            $query->orWhere('slug', 'like', "%{$filter['search_text']}%");
            $query->orWhere('content', 'like', "%{$filter['search_text']}%");

        }

        if (isset($filter['article_category_id']) && $filter['article_category_id']) {
            $query->where('article_category_id', '=', $filter['article_category_id']);
        }

        return $query;
    }



   public function store(Request $request)
   {
    // dd($request);
    if($request['id'] != null){
      $articleId = $request['id'];

      try {
         DB::beginTransaction();

          if(isset($request['correction']) && ($request['correction'] == 'correction')){
            // dd($request->all());
            $prevData = $this->find($request['id']);
            $prevData->end_date = date('Y-m-d h:i:s');
            $prevData->updated_by = $request['created_by'];
            $prevData->save();


            $request['ref_id'] = $request['id'];
            $request['id'] = NULL;
            $articleId = Article::create($request->all())->id;
          }
          else{
            $prevData = $this->find($request['id']);
            $prevData->title = $request['title'];
            $prevData->slug = $request['slug'];
            $prevData->article_category_id = $request['article_category_id'];
            $prevData->content = $request['content'];
            $prevData->complaint_path = $request['complaint_path'];
            $prevData->review_status = $request['review_status'];
            $prevData->start_date = $request['start_date'];
            $prevData->end_date = $request['end_date'];
            $prevData->updated_by = $request['created_by'];
            $prevData->save();
          }


          ArticleTag::where('article_id', $articleId)->delete();

          $tagData = TagKey::where('title', $request['tag_name'])->first();
          // dd($tagData);
          if($tagData == null){
            $tagKey = new TagKey();
            $tagKey->title = $request['tag_name'];
            $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
            $tagKey->save();
            $tagId = $tagKey->id;

            $articleTag = new ArticleTag();
            $articleTag->tag_key_id = $tagId;
            $articleTag->article_id = $articleId;
            $articleTag->save();
          }
          else{
            $articleTag = new ArticleTag();
            $articleTag->tag_key_id = $tagData['id'];
            $articleTag->article_id = $articleId;
            $articleTag->save();
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

      try {
         DB::beginTransaction();
        $articleId = Article::create($request->all())->id;

        $tagData = TagKey::where('title', $request['tag_name'])->first();
        // dd($tagData);
        if($tagData == null){
          $tagKey = new TagKey();
          $tagKey->title = $request['tag_name'];
          $tagKey->slug = str_replace(' ', '-', $request['tag_name']);
          $tagKey->save();
          $tagId = $tagKey->id;

          $articleTag = new ArticleTag();
          $articleTag->tag_key_id = $tagId;
          $articleTag->article_id = $articleId;
          $articleTag->save();
        }
        else{
          $articleTag = new ArticleTag();
          $articleTag->tag_key_id = $tagData['id'];
          $articleTag->article_id = $articleId;
          $articleTag->save();
        }

         DB::commit();
         return 1;

      }
       catch (\Exception $e) {
         DB::rollBack();
         return $e->getMessage();

      }


      // return Article::create($request->all());
    }
   }



}
