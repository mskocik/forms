<?php declare(strict_types=1);

namespace Mskocik\Forms\Controls;

use Mskocik\Forms\Utils\SvelecteTrait;
use Nette\Forms\Controls\MultiSelectBox;

class SvelecteMultiSelect extends MultiSelectBox
{
    use SvelecteTrait;

    private $multiple = true;

    public function getValue(): array
    {
        return $this->allowOutOfValues
            ? array_filter($this->value)
            : array_filter(parent::getValue())
        ;
    }
}
