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
final class TextareaField implements FieldInterface
{
    public final const ROW = 'rows';
    public final const COL = 'cols';
    private static array $defaultParams = [ self::ROW => 2,self::COL => null ];

    use FormFieldTrait;

    public static function init(string $name, ?string $label = null, ...$params): self
    {
        $label = $label ?? self::humanizeString($name);
        $finalParams = $params + self::$defaultParams;
        return (new self())
        ->setName($name)
        ->setComponent('native::crudboard.fields.textarea')
        ->setLabel($label)
        ->setCustomOption(self::ROW, $finalParams[self::ROW])
        ->setPlaceholder($label);
    }

    public function setNumOfRows(int $rows): self
    {
        if ($rows < 1) {
            throw new \InvalidArgumentException(sprintf('The argument of the "%s()" method must be 1 or higher (%d given).', __METHOD__, $rows));
        }
        $this->setCustomOption(self::ROW, $rows);
        return $this;
    }
    

}