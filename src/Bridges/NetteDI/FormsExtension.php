<?php declare(strict_types=1);

namespace Mskocik\Forms\Bridges\NetteDI;

use Nette\Forms\Container;
use Mskocik\Forms\Controls\SvelecteSelect;
use Mskocik\Forms\Controls\SvelecteMultiSelect;
use Mskocik\Forms\Controls\SveltyPicker;

class FormsExtension extends \Nette\DI\CompilerExtension
{
    public function loadConfiguration()
    {
        $initialize = $this->initialization;
		$initialize->addBody(static::class . '::init();');
    }

	private static $initialized = false;

	/**
	 * Init all custom controls
	 *
	 * @return void
	 */
	public static function init(): void
	{
		if (static::$initialized) return;

		Container::extensionMethod('addSvelecteSelect', function(Container $form, string $name, ?string $label = null, ?array $items = null): SvelecteSelect {
			return $form[$name] = new SvelecteSelect($label, $items);
		});
		Container::extensionMethod('addSvelecteMultiSelect', function(Container $form, string $name, ?string $label = null, ?array $items = null): SvelecteMultiSelect {
			return $form[$name] = new SvelecteMultiSelect($label, $items);
		});
		Container::extensionMethod('addDatePicker', function(Container $form, string $name, ?string $label = null, ?string $format = null): SveltyPicker {
			return $form[$name] = new SveltyPicker($label, $format);
		});
		static::$initialized = true;
	}
}