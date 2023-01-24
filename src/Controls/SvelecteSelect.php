<?php declare(strict_types=1);

namespace Mskocik\Forms\Controls;

use Nette\Forms\Controls\SelectBox;
use Mskocik\Forms\Utils\Svelecte;

class SvelecteSelect extends SelectBox
{
    use Svelecte;

    private $multiple = false;
}
