<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Services\CrudBoard;


/**
 * This interface defines blueprints of NativeBL CRUD grid loader.  
 * It will ensure to provide record for CrudBoard grid.
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

 use NativeBL\Contracts\Repository\NativeRepositoryInterface;
 use NativeBL\Field\ButtonField;

final class CrudBoard extends AbstractCrudBoard
{      
   private $params = [];
   private $actions = [];

   public function check()
   {
      echo __CLASS__;
   } 

   public function setParam($param)
   {
      $this->params[] = $param;
   }

   public function getParams() : array
   {
      return $this->params;
   }

   public function setRepository(NativeRepositoryInterface $repo) : self
   {
        $this->repo = $repo;
        return $this;
   }

   protected function getRecordForShow(int|string $id): ?\ArrayAccess
   {
       return $this->getRepository()->crudShow($id);
   }
}