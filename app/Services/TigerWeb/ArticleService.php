<?php

namespace App\Services\TigerWeb;

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


    public function getAllArticle() : void
    {

    }

    public function details($id)
    {
        return $this->articleRepository->details($id);
    }

    public function store(Request $request)
    {
        return $this->articleRepository->store($request);
    }


    public function showAllArticle($input)
    {
        return $this->articleRepository->articleFilterData($input);
    }


}
