<?php declare(strict_types=1);

use Nette\Forms\Form;
use Mskocik\Forms\Controls\SveltyPicker;
use Mskocik\Forms\Controls\SvelecteSelect;
use Mskocik\Forms\Controls\SvelecteMultiSelect;
use Mskocik\Forms\Bridges\NetteDI\FormsExtension;

require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * @method SveltyPicker             addDatePicker(string $name, ?string $label = null, ?string $format = null)
 * @method SvelecteSelect           addSvelecteSelect(string $name, ?string $label = null, ?array $items = null)
 * @method SvelecteMultiSelect      addSvelecteMultiSelect(string $name, ?string $label = null, ?array $items = null)
 */ 
class TestForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        FormsExtension::init();
    }
}