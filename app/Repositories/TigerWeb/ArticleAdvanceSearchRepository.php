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

use App\Contracts\TigerWeb\ArticleAdvanceSearchRepositoryInterface;
use Illuminate\Support\Facades\DB;
use NativeBL\Repository\AbstractNativeRepository;
use App\Models\TigerWeb\Article;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use Illuminate\Support\Collection;

class ArticleAdvanceSearchRepository extends AbstractNativeRepository implements ArticleAdvanceSearchRepositoryInterface, CrudGridLoaderInterface
{

    public function getModelFqcn(): string
    {
        return Article::class;
    }


    public function searchArticles($searchTerm): Collection
    {
        return DB::table('articles')
            ->select('articles.title', 'articles.content', 'articles.id as article_id')
            ->leftJoin('article_tags', 'articles.id', '=', 'article_tags.article_id')
            ->leftJoin('tag_keys', 'article_tags.tag_key_id', '=', 'tag_keys.id')
            ->where('articles.title', 'like', "%{$searchTerm}%")
            ->orWhere('articles.content', 'like', "%{$searchTerm}%")
            ->orWhere('tag_keys.title', 'like', "%{$searchTerm}%")
            ->distinct()
            ->limit(5)
            ->get();
    }

    public function searchFaqs($searchTerm): Collection
    {
        return DB::table('faqs')
            ->select('faqs.question', 'faqs.answer', 'faqs.ref_id as article_id', 'faqs.id as faq_id')
            ->leftJoin('faq_tags', 'faqs.id', '=', 'faq_tags.faq_id')
            ->leftJoin('tag_keys', 'faq_tags.tag_key_id', '=', 'tag_keys.id')
            ->where('faqs.faq_type', "ARTICLE")
            ->where('faqs.question', 'like', "%{$searchTerm}%")
            ->orWhere('faqs.answer', 'like', "%{$searchTerm}%")
            ->orWhere('tag_keys.title', 'like', "%{$searchTerm}%")
            ->distinct()
            ->limit(5)
            ->get();
    }

}
