<?php declare(strict_types=1);

/*
 * This file is part of the nativebl package.
 *
 * (c) Banglalink Digital Communications Limited <info@banglalink.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NativeBL\Contracts\Field;


/**
 * This interface defines blueprints of field for curd boad components i.e. grid, form and filters   
 * 
 *
 * @author Muhammad Abdullah Ibne Masud <abdullah.masud@banglalink.net>
 */

interface FieldInterface
{
    public static function init(string $name, ?string $label = null, ...$params): self;

}