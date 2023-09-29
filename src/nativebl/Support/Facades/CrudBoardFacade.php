<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Support\Facades;

use Illuminate\Support\Facades\Facade;
use NativeBL\Contracts\Service\CrudBoard\CrudBoardInterface;

/**
 * This is a facade  class for crud grid
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

class CrudBoardFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CrudBoardInterface::class;
    }
}