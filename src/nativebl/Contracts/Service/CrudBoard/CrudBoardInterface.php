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

use NativeBL\Contracts\Repository\NativeRepositoryInterface;
use NativeBL\Form\AbstractForm;

/**
 * This interface defines blueprints of CRUD Board. CRUD Board is will handle CRUD operations 
 * as well as filter and sorting options.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface CrudBoardInterface
{
   public function createGrid(CrudGridLoaderInterface $dataLoader, array $params = []) : CrudGridInterface;
   public function getRepository(): NativeRepositoryInterface;
   public function setRepository(NativeRepositoryInterface $repo): self;
   public function getGrid(): CrudGridInterface;
   public function addGridActions(array $actions=[]);
   public function getForm() : AbstractForm;
   public function createForm(array $fields);
   public function getCrudShow() : CrudShowInterface;
}