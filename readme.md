# Form custom elements

Provide backend implementation of some svelte custom elements ([svelecte](https://github.com/mskocik/svelecte) and [svelty-picker](https://github.com/mskocik/svelty-picker))

## Install

```bash
composer require mskocik/forms
```

## Register extension

```neon
extensions:
    ext.forms: Mskocik\Forms\Bridges\NetteDI\FormsExtension
```

If you are using [nette/forms](https://github.com/nette/forms) standalone, call control registration manually:

```php
\Mskocik\Forms\Bridges\NetteDI\FormsExtension::init()
```

## Extend nette form for IDE autocomplete

Extend `Nette\Application\UI\Form` (or `Nette\Forms\Form` if using standalone forms) and also `Nette\Forms\Container` with extended PHPDoc block:

```php
<?php declare(strict_types=1);

use Nette\Application\UI\Form;
use Nette\Forms\Container;

/**
 * @method SveltyPicker 			addDatePicker(string $name, ?string $label = null, ?string $format = null)
 * @method SvelecteSelect			addSvelecteSelect(string $name, ?string $label = null, ?array $items = null)
 * @method SvelecteMultiSelect  	addSvelecteMultiSelect(string $name, ?string $label = null, ?array $items = null)
 */
class AppForm extends Form
{}

/**
 * @method SveltyPicker 			addDatePicker(string $name, ?string $label = null, ?string $format = null)
 * @method SvelecteSelect			addSvelecteSelect(string $name, ?string $label = null, ?array $items = null)
 * @method SvelecteMultiSelect  	addSvelecteMultiSelect(string $name, ?string $label = null, ?array $items = null)
 */
class AppFormContainer extends Container
{}
```
## Use as needed!

```php
$form = new Form();
$form->addSvelecteSelect('select', 'My Select', [/** item array */])
    ->setFetch($presenter->link('Api:fetch', ['id' => '[query]']))
    ->setRequired();
// ...
```

