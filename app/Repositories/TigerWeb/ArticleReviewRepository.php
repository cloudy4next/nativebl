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
use App\Contracts\TigerWeb\ArticleReviewRepositoryInterface;
use App\Models\TigerWeb\ArticleReview;
use App\Models\TigerWeb\Article;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;





class ArticleReviewRepository  extends AbstractNativeRepository implements ArticleReviewRepositoryInterface, CrudGridLoaderInterface
{

   public function getModelFqcn(): string
   {
     return ArticleReview::class;
   }

   public function getGridData() : iterable
   {
       return $this->all();
   }


   public function articleReviewFilterData($filter)
    {
        //return $query = $this->all();
        $query = ArticleReview::query();
        if (isset($filter['search_text']) && $filter['search_text']) {
           // $query->where('title', 'like', "%{$filter['search_text']}%");
           // $query->where('slug', 'like', "%{$filter['search_text']}%");

        }

        return $query;
    }


   public function raiseApproveTicket($data)
    {
        $article = Article::find($data['article_id']);

        $article->review_status = $data['review_status'];
        $article->save();

       // $articleReview = ArticleReview::create($data);
        $articleReview = new ArticleReview();
        $articleReview->article_id = $data['article_id'];
        $articleReview->review_status = $data['review_status'];
        $articleReview->review_comments = $data['review_comments'];
        $articleReview->created_by = $data['created_by'];
        $articleReview = $articleReview->save();
        return $articleReview;
    }


}
