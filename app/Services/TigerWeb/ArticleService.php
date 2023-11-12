<?php

namespace App\Services\TigerWeb;

use App\Http\Requests\TigerWeb\ArticleRequest;
use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\ArticleServiceInterface;

use App\Repositories\TigerWeb\ArticleRepository;
use Illuminate\Http\Request;

final class ArticleService implements ArticleServiceInterface

{

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {

        $this->articleRepository = $articleRepository;
    }


    public function getAllArticle(): void
    {

    }

    public function details($id)
    {
        return $this->articleRepository->details($id);
    }

    public function slugDetails($slug)
    {
        return $this->articleRepository->slugDetails($slug);
    }

    public function store(ArticleRequest $request)
    {
        return $this->articleRepository->store($request);
    }

    public function article_review_submit(Request $request)
    {
        return $this->articleRepository->article_review_submit($request);
    }

    public function articleArchive($id)
    {
        return $this->articleRepository->articleArchive($id);
    }

    public function showAllArticle($input)
    {
        return $this->articleRepository->articleFilterData($input);
    }


}
