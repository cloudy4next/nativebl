<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Service\CrudBoard;

use NativeBL\Collection\ActionCollection;
use NativeBL\Collection\FieldCollection;

/**
 * This interface defines blueprints of NativeBL CRUD grid loader.  
 * It will ensure to provide record for CrudBoard grid.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface CrudShowInterface
{
   function getActions(): ActionCollection;
   function getFields(): FieldCollection; 
   function getRecord(): \ArrayAccess;
}