<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Field;


use NativeBL\Contracts\Field\FieldInterface;


/**
 * This class is for creating text field .
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */
final class ChoiceField implements FieldInterface
{
    final const LIST   = 'choiceList';
    final const TYPE   = 'choiceType';
    final const EMPTY  = 'empty';
    final const SELECTED  = 'selected';

    use FormFieldTrait;

    public static function init(string $name, ?string $label = null, ...$params) : self
    {
         $type = isset($params[0]) ?  $params[0] : (isset($params[self::TYPE])? $params[self::TYPE] :'select');
         $choiceList = isset($params[1]) ?  $params[1] : (isset($params[self::LIST])? $params[self::LIST] : null);  
         $empty = isset($params[2]) ?  $params[2] : (isset($params[self::EMPTY])? $params[self::EMPTY] : null);  
         $selected = isset($params[3]) ?  $params[3] : (isset($params[self::SELECTED])? $params[self::SELECTED] : null);  
         !$choiceList && throw new \InvalidArgumentException(self::LIST.':[] is a mandatory  parameter');
         return (new self($type,$choiceList))
        ->setName($name)
        ->setComponent('native::crudboard.fields.choice')
        ->setLabel($label ?? self::humanizeString($name))
        ->setCustomOption(self::LIST,$choiceList)
        ->setCustomOption(self::EMPTY,$empty)
        ->setDefault($selected)
        ->setInputType($type)
        ;
        
    }

    public function setDefault(mixed $defaultValue) : self
    {

        if(is_array($defaultValue)) {
            $this->setAttribute('multiple',false);
            $values = \array_flip($defaultValue);
        }else {
            $values[$defaultValue] = 1;
        }
        $this->setCustomOption(self::SELECTED, $values);
        return $this;
    }


    
}