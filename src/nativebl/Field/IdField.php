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
final class IdField implements FieldInterface
{
    use FormFieldTrait;

    public static function init(string $name, ?string $label = null, ...$params): self
    {
        return (new self())
        ->setName($name)
        ->setComponent('native::crudboard.fields.id')
        ->setHtmlAttributes(['type'=>'hidden'])
        ->setLabel($label ?? self::humanizeString($name));
    }
    

}