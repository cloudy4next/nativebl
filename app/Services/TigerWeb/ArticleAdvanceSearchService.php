<?php

namespace App\Services\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleAdvanceSearchServiceInterface;
use App\Repositories\TigerWeb\ArticleAdvanceSearchRepository;
use Illuminate\Support\Collection;

final class ArticleAdvanceSearchService implements ArticleAdvanceSearchServiceInterface

{
    /**
     * @param ArticleAdvanceSearchRepository $articleAdvanceSearchRepository
     */
    public function __construct(private readonly ArticleAdvanceSearchRepository $articleAdvanceSearchRepository)
    {

    }


    /**
     * @return void
     */
    public function getAllArticle(): void
    {

    }

    /**
     * @param $searchTerm
     * @return Collection
     */
    public function searchArticles($searchTerm): Collection
    {
        return $this->articleAdvanceSearchRepository->searchArticles($searchTerm);
    }


    /**
     * @param $searchTerm
     * @return Collection
     */
    public function searchFaqs($searchTerm): Collection
    {
        return $this->articleAdvanceSearchRepository->searchFaqs($searchTerm);
    }

}
