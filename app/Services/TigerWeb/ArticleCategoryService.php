<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\ArticleCategoryServiceInterface;

use App\Repositories\TigerWeb\ArticleCategoryRepository;
use Illuminate\Http\Request;

final class ArticleCategoryService implements ArticleCategoryServiceInterface

{

	private $articleCategoryRepository;

	public function __construct(ArticleCategoryRepository $articleCategoryRepository)
    {

        $this->articleCategoryRepository = $articleCategoryRepository;
    }

    public function getAllArticleCategory() : void
    {

    }

    public function store(Request $request)
    {
        return $this->articleCategoryRepository->store($request);
    }

    public function showAllArticleCategory($input)
    {
        return $this->articleCategoryRepository->articleCategoryFilterData($input);
    }


    public function updateArticleCategory($id, $data)
    {
        return $this->articleCategoryRepository->updateArticleCategory($id, $data);
    }
}
