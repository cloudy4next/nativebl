<?php

namespace App\Services\TigerWeb;

use App\Contracts\Services\TigerWeb\FaqServiceInterface;
use App\Repositories\TigerWeb\FaqRepository;
use Illuminate\Http\Request;

class FaqService implements FaqServiceInterface
{
    private $faqRepository;

    public function __construct(FaqRepository $faqRepository)
    {

        $this->faqRepository = $faqRepository;
    }


    public function getAllFaq() : void
    {

    }

    public function details($id)
    {
        return $this->faqRepository->details($id);
    }

    public function store(Request $request)
    {
        return $this->faqRepository->store($request);
    }


    public function showAllArticle($input)
    {
        return $this->faqRepository->faqFilterData($input);
    }

    public function delete($id)
    {
        return $this->faqRepository->delete($id);
    }
}
