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

/**
 * This interface defines blueprints of NativeBL CRUD form data handler.
 * It define how to  validate and store data to DB   
 * It will ensure to provide record for CrudBoard grid.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface CrudFormHandlerInterface
{
   function saveFormData( array $data=[]);
}