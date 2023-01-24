<?php declare(strict_types=1);

namespace Mskocik\Forms\Utils;

use Nette\Utils\Html;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\ChoiceControl;

trait Svelecte
{
    static $element = 'el-svelecte';

    static $classList = ['el-svelecte', 'svelecte-control'];

    private $svelecteProps = [];

    private $allowOutOfValues = false;

    private $svelecteParent = null;

    private $itemMapper = null;

    private $optionsMapper = null;

    private $rawItems = [];

	/**
	 * Sets options and option groups from which to choose.
     * 
	 * @return static
	 */
	public function setItems(array $items, bool $useKeys = true)
    {
        $this->rawItems = $useKeys ? $items :  array_combine($items, $items);
        return parent::setItems($items, $useKeys);
    }

    /**
     * Converter function to transform _single_ option to object for svelecte component
     * 
     * @param callable $callback
     * @return static
     */
    public function setItemMapper(callable $callback)
    {
        $this->itemMapper = $callback;
        return $this;
    }
    
    /**
     * Use for custom mapping of $items to object array. Callback receives $opts, which should be array
     * or array-like object (iterable)
     * 
     * @param callable $callback
     * @return static
     */
    public function setOptionsMapper(callable $callback)
    {
        $this->optionsMapper = $callback;
        return $this;
    }

    /** ************************************ setters */

    /**
     * Define [query] placeholder for search query and (or) [parent] for parent value
     * 
     * @param ?string $fetch
     * @return static
     */
    public function setFetch(?string $fetch = null)
    {
        $this->svelecteProps['fetch'] = $fetch ? urldecode($fetch) : $fetch;
        $this->checkDefaultValue(false);
        return $this;
    }

    /**
     * Set parent svelecte, which will control fetch list of this one. Call $parent->getHtmlId('svelecte')
     * when passing $parentHtmlId
     * 
     * @param string $parentHtmlId
     * @return static
     */
    public function setParentSvelecte(string $parentHtmlId)
    {
        $this->svelecteParent = $parentHtmlId;
        return $this;
    }

    /**
     * @param array $props
     * @return static
     */
    public function setSvelectePropArray(array $props)
    {
        $this->svelecteProps = $this->svelecteProps + $props;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return static
     */
    public function setSvelecteProp(string $name, $value)
    {
        $this->svelecteProps[$name] = $value;
        return $this;
    }

    /** ************************************ overrides */
    /**
     * @override
     * @return mixed
     */
    public function getValue()
    {
        if ($this->allowOutOfValues) return $this->value ?: null;

        $parentValue = parent::getValue();
        return $parentValue !== '' ? $parentValue : null;
    }
    /**
     * @override
     * @param bool $value
     * @return static
     */
    public function checkDefaultValue(bool $value = true)
    {
        parent::checkDefaultValue($value);
        $this->allowOutOfValues = !$value;
        return $this;
    }
    /**
     * @override
     * @param string $prompt
     * @return static
     */
    public function setPrompt($prompt)
    {
        if ($this instanceof SelectBox) {
            parent::setPrompt($prompt);
        }
        $this->setSvelecteProp(SvelecteProps::PLACEHOLDER, $prompt);

        return $this;
    }

    /**
     * For dependent selects this must be called with argument 'svelecte'
     * 
     * @override
     * @return string
     */
    public function getHtmlId(): string
    {
        $id = parent::getHtmlId();
        if (func_num_args() > 0) {
            $id.= '_' . func_get_arg(0);
        }
        return $id;
    }

    /** ************************************ rendering */

    public function getControl(): Html
    {
        // $opts = Helpers::buildSvelecteOptions($rawItems, $this->propertyMapping);
        parent::setItems([]);
        /** @var Html*/
        $select = parent::getControl();
        $select->removeAttributes(['class']);
        $select->removeChildren();
        $formattedOptions = $this->optionsMapper
            ? call_user_func_array($this->optionsMapper, [$this->rawItems, $this->itemMapper])
            : SvelecteHelper::use($this->itemMapper)($this->rawItems);
        // standard attributes
        $attrs = [
            'id' => $this->getHtmlId('svelecte'),
            'name' => '',
            'options' => json_encode($formattedOptions),
            'class' => implode(' ', static::$classList),
            'anchor' => $select->id,
            // 'searchable' => $this->searchable,
            'multiple' => $this->multiple,
            'disabled' => $this->disabled ? 'disabled' : false,
            'value' => $this->multiple && is_array($this->value)
                ? implode(',', $this->value)
                : $this->value,
        ];
        if (!empty($this->svelecteProps)) $attrs = array_merge($attrs, $this->svelecteProps);

        if ($this->svelecteParent) {
            $attrs['parent'] = $this->svelecteParent instanceof ChoiceControl
                ? $this->svelecteParent->getHtmlId('svelecte')
                : (is_string($this->svelecteParent) ? $this->svelecteParent : $this->svelecteParent->getHtmlId());
        }

        $el = Html::el(static::$element, $attrs);
        // set initially selected options for proper form validation initialization
        $selectedOptions = is_array($this->value) ? $this->value : [$this->value];
        foreach ($selectedOptions as $i => $val) {
            $select->insert($i, "<option value='$val' selected>$val</option>", true);
        }
        $el->insert(null, $select);

        return $el;
    }
}
