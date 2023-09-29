<?php

namespace App\Services\ToffeAnalytics;

use Carbon\Carbon;
use NativeBL\Repository\AbstractNativeRepository;
use NativeBL\Contracts\Service\CrudBoard\CrudGridLoaderInterface;
use App\Contracts\Services\ToffeAnalytics\UserCampaignServiceInterface;

final class UserCampaignService  implements UserCampaignServiceInterface
{


    public function __construct(ArticleRepository $articleRepository)
    {

        $this->articleRepository = $articleRepository;
    }

    public function getModelFqcn(): string
    {
        return '';
    }
    public function getGridData(): iterable
    {
        // dd($this->prepareArray());
        return $this->prepareArray();
    }



}
