<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\ArticleTagServiceInterface;

use App\Repositories\TigerWeb\ArticleTagRepository;

final class ArticleTagService implements ArticleTagServiceInterface

{

	private $articleTagRepository;

	public function __construct(ArticleTagRepository $articleTagRepository)
    {

        $this->articleTagRepository = $articleTagRepository;
    }


    public function getAllArticleTag() : void
    {

    }


    public function showAllArticleTag($input)
    {
        return $this->articleTagRepository->articleTagFilterData($input);
    }

}
