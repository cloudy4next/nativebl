<?php

namespace App\Http\Controllers\TigerWeb;

use App\Contracts\Services\TigerWeb\ArticleAdvanceSearchServiceInterface;
use App\Services\TigerWeb\CommonService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use NativeBL\Controller\AbstractNativeController as AbstractController;
use App\Contracts\TigerWeb\ArticleAdvanceSearchRepositoryInterface;

class ArticleAdvanceSearchController extends AbstractController
{
    /**
     * @param ArticleAdvanceSearchRepositoryInterface $repo
     * @param ArticleAdvanceSearchServiceInterface $articleAdvanceSearchService
     * @param CommonService $commonService
     */
    public function __construct(private readonly ArticleAdvanceSearchRepositoryInterface $repo, private readonly ArticleAdvanceSearchServiceInterface $articleAdvanceSearchService, private readonly CommonService $commonService)
    {
    }

    /**
     * @return ArticleAdvanceSearchRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repo;
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function search(Request $request): \Illuminate\Foundation\Application|View|Factory|Application
    {
        $searchTerm = trim($request->input('search_term'));
        $articleResults = collect();
        $faqResults = collect();
        if (!empty($searchTerm)) {
            $articleResults = $this->commonService->highlightKeyword($searchTerm, $this->articleAdvanceSearchService->searchArticles($searchTerm), ['title', 'content']);
            $faqResults = $this->commonService->highlightKeyword($searchTerm, $this->articleAdvanceSearchService->searchFaqs($searchTerm), ['answer', 'question']);
        }

        return view('home.TigerWeb.Article.advance-search-accordion', compact('articleResults', 'faqResults'));
    }


}
