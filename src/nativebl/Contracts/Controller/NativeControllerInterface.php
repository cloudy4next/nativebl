<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Controller;

use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Repository\AbstractNativeRepository;

/**
 * This interface defines blueprints of NativeBL admin controller.  
 * as well as filter and sorting options.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface NativeControllerInterface
{
    function getRepository();
    function initGrid(array $columns);
}