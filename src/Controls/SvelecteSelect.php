<?php declare(strict_types=1);

namespace Mskocik\Forms\Controls;

use Nette\Forms\Controls\SelectBox;
use Mskocik\Forms\Utils\SvelecteTrait;

class SvelecteSelect extends SelectBox
{
    use SvelecteTrait;

    private $multiple = false;
}
