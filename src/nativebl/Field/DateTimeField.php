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
 * This class is for field creation in crudboard  .
 *
 * @author Muhammad Abdullah Ibne Masud <md.a.ibne.masud@gmail.com>
 */

final class DateTimeField implements FieldInterface
{
    use FormFieldTrait;

    public static function init(string $name, ?string $label = null, ...$params): self
    {
        return (new self())
        ->setName($name)
        ->setLabel($label ?? self::humanizeString($name))
        ->setComponent('native::crudboard.fields.datetime')
        ->formatValue(fn($value):string => (new \DateTime($value))->format('Y-m-d') );
    }

   

}