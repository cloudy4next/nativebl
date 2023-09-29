<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\ArticleReviewServiceInterface;

use App\Repositories\TigerWeb\ArticleReviewRepository;

final class ArticleReviewService implements ArticleReviewServiceInterface

{

	private $articleReviewRepository;

	public function __construct(ArticleReviewRepository $articleReviewRepository)
    {

        $this->articleReviewRepository = $articleReviewRepository;
    }


    public function getAllArticleReview() : void
    {

    }


    public function showAllArticleReview($input)
    {
        return $this->articleReviewRepository->articleReviewFilterData($input);
    }


    public function raiseApproveTicket($data)
    {
        return $this->articleReviewRepository->raiseApproveTicket($data);
    }

}
