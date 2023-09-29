<?php

namespace App\Services\TigerWeb;


use App\Repositories\TigerWeb\CommonRepository;

final class CommonService

{

	private $commonRepository;

	public function __construct(CommonRepository $commonRepository)
    {

        $this->commonRepository = $commonRepository;
    }

    public function articleList()
    {
        return $this->commonRepository->articleList();
    }

    public function articleCategoryList()
    {
        return $this->commonRepository->articleCategoryList();
    }

    public function faqList()
    {
        return $this->commonRepository->faqList();
    }
}
