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

use App\Models\TigerWeb\Article;
use App\Models\TigerWeb\ArticleCategory;
use App\Models\TigerWeb\Faq;


class CommonRepository
{

  public function articleList()
   {
    $articles = Article::whereRaw("CURDATE() between start_date and end_date")
						->orWhereNull('end_date')
						->pluck('title','id');
    return $articles;
   }

  public function articleCategoryList()
   {
    $articleCategories = ArticleCategory::whereNull('updated_by')->pluck(
      'title',
      'id'
    );
    return $articleCategories;
   }

    public function faqList()
    {
        $faqs = Faq::pluck('id');
        return $faqs;
    }
}
