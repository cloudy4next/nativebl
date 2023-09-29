<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Repository;

/**
 * This interface defines blueprints of NativeBL Repository.  
 * 
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface NativeRepositoryInterface
{
   public function getModelFqcn(): string;
   public function crudShow(int|string $id): ?\ArrayAccess ; 
}