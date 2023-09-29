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

use NativeBL\Form\AbstractForm;
use NativeBL\Collection\ActionCollection;
use Illuminate\Support\Facades\Request;
 /**
  * This is an abstract form  class for crud board
  *
  * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
  */
 
  class GridFilter extends AbstractForm 
  {
    const CONTAINER_NAME = 'filters';
     
    public function getActions() : ActionCollection
    {
        return $this->actions->getFilterActions();
    }

    public function assignQueryData() : self
    {
       $queryData =  Request::get(self::CONTAINER_NAME,[]);
       $fields = $this->getFields();
       foreach($queryData as $field=>$value){
        if($fields->offsetExists($field)) {
         $value &&  $fields->get($field)->setValue($value);
        }
       } 
       return $this;
    }

    public function getData(): array
    {
       $data =  Request::get(self::CONTAINER_NAME,[]);
       return array_filter($data);
    }


  }