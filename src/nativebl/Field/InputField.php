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
final class InputField implements FieldInterface
{
    use FormFieldTrait;

    public static function init(string $name, ?string $label = null, ...$params): self
    {
        $type = isset($params[0]) ?  $params[0] : (isset($params['type'])? $params['type'] :'text');
        $value = isset($params[1]) ?  $params[1] : (isset($params['value'])? $params['value'] : null );
        return (new self())
        ->setName($name)
        ->setComponent('native::crudboard.fields.input')
        ->setHtmlElementName('input')
        ->setPlaceholder($label ?? self::humanizeString($name))
        ->setInputType($type)
        ->setLabel($label ?? self::humanizeString($name))
        ->setDefaultValue($value)
        ;
    }

    
    

}