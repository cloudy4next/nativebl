<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\DailyNewsServiceInterface;

use App\Repositories\TigerWeb\DailyNewsRepository;

final class DailyNewsService implements DailyNewsServiceInterface

{

	private $dailyNewsRepository;

	public function __construct(DailyNewsRepository $dailyNewsRepository)
    {

        $this->dailyNewsRepository = $dailyNewsRepository;
    }


    public function getAllDailyNews() : void
    {

    }


    public function showAllDailyNews($input)
    {
        return $this->dailyNewsRepository->dailyNewsFilterData($input);
    }

}
