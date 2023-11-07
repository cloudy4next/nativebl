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

use NativeBL\Collection\ActionCollection;
use NativeBL\Contracts\Service\CrudBoard\CrudShowInterface;
use NativeBL\Collection\FieldCollection;

class CrudShow implements CrudShowInterface
{
    
    private FieldCollection $fields;
    private ActionCollection $actions;
    
    private function __construct( array $fields, private \ArrayAccess $record)
    {
       $this->addFields($fields)
       ->prepareRecord($record);   

    }

    public static function init(array $fields, \ArrayAccess $record)
    {
         return new self($fields,$record);
    }

    public function addFields($fields)
    {
      $this->fields =  FieldCollection::init($fields);
      return $this;
    }

   public function addActions(array $actions=[])
   {
      $dtos = []; 
      foreach($actions as $name => $action){
       $dtos[$name] = $action->getDto();
      }
      $this->actions = ActionCollection::init($dtos);
      return $this;
   }

   public function getActions() : ActionCollection
   {
     return $this->actions;
   }

   public function getFields(): FieldCollection
   {
     return $this->fields;
   }

   private function prepareRecord($record)
   {
      foreach($this->fields as $name=>&$field)
      {
         $field->setValue($record[$name]);
      }
   }

   public function getRecord() : \ArrayAccess
   {
      return $this->record;
   }
}