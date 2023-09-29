<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace NativeBL\Form;

use ArrayAccess;
use NativeBL\Collection\ActionCollection;
use NativeBL\Collection\FormFieldCollection;
use NativeBL\Contracts\Service\CrudBoard\CrudFormHandlerInterface;
use NativeBL\Dto\CrudBoard\FormFieldDto;
use NativeBL\Field\IdField;

 /**
  * This is an abstract form  class for crud board
  *
  * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
  */
 
 abstract class AbstractForm 
 {  
    const STAT_NEW         = 'new';
    const STAT_EDIT        = 'edit';
    const STAT_NEW_SUBMIT  = 'new_submit';
    const STAT_EDIT_SUBMIT = 'edit_submit';
    private  $formStat;
    private string $actionUrl;
    private string $name;
    private array $attributes = [];
    private string $cssClass  = '';
    private string $method = 'post';
    private bool $isSubmit = false;
    private FormFieldCollection $fields;
    private CrudFormHandlerInterface $handler;
    protected ActionCollection $actions;
    private $data;
    
    public function addFields(array $fields)
    {
        $this->fields = FormFieldCollection::init($fields);
        return $this;
    }

    public function addField($field)
    {
        $this->fields->add($field);
    }

    function getFields() : FormFieldCollection
    {
        return $this->fields;
    }

    public function setFormHandler(CrudFormHandlerInterface $handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function saveData(array $data)
    {
     
       $this->data =  $this->handler->saveFormData($data);
       return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setActionUrl(string $url)
    {
        $this->actionUrl = $url;
        return $this;
    }

    public function getActionUrl(): string
    {
        return $this->actionUrl;
    }

    public function setCssClass(string $cssClass)
    {
        $this->cssClass .= ' '.$cssClass;
        return $this;
    }

    public function getCssClass(): string
    {
        return $this->cssClass;
    }

    public function getAttributesAsHtml(): string
    {
        $html = '';
        foreach($this->attributes as $formAttr=>$val) {
            $html .= ($formAttr == 'class') ? $this->setCssClass($val) : "$formAttr='$val' ";
        };
        return $html;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = \array_merge($this->attributes,$attributes);
        return $this;
    }

    public function getAttributes(): array
    {
       return $this->attributes;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setActions(array $actions) : static
    {
        $dtos = [];
        foreach($actions as $action) {
            $dto = $action->getDto();
            $dto->isFormAction() &&  $dtos[] = $dto;  
        }
        $this->actions = ActionCollection::init($dtos);
        return $this;
    }

  
    public function setData(\ArrayAccess $data) : static
    {
        foreach($this->fields as $name=>&$dto){
           $dto->setValue($data[$name]);
        }
        return $this;
    }

    public function addIdField(string $name='id'): static
    {
        $field = IdField::init($name);
        $this->addField($field);
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setFormStat(string $stat): static
    {
        $this->formStat = $stat;
        return $this;
    }

    public function isNew()
    {
        return (self::STAT_NEW === $this->formStat); 
    }


    public function isEdit()
    {
        return (self::STAT_EDIT === $this->formStat); 
    }


    

   
 }