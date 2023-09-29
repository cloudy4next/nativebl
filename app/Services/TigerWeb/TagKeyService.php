<?php

namespace App\Services\TigerWeb;

use App\Models\TigerWeb\User;
use App\Contracts\Services\TigerWeb\TagKeyServiceInterface;

use App\Repositories\TigerWeb\TagKeyRepository;

final class TagKeyService implements TagKeyServiceInterface

{

	private $tagKeyRepository;

	public function __construct(TagKeyRepository $tagKeyRepository)
    {

        $this->tagKeyRepository = $tagKeyRepository;
    }


    public function getAllTagKey() : void
    {

    }


    public function showAllTagKey($input)
    {
        return $this->tagKeyRepository->tagKeyFilterData($input);
    }

}
