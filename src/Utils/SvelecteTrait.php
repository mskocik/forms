<?php declare(strict_types=1);

namespace Mskocik\Forms\Utils;

use Nette\Utils\Html;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\ChoiceControl;

trait SvelecteTrait
{
    static $element = 'el-svelecte';

    static $classList = ['el-svelecte', 'svelecte-control'];

    private $svelecteProps = [];

    private $allowOutOfValues = false;

    private $svelecteParent;

    private $itemMapper = null;

    private $rawItems;

	/**
	 * Sets options and option groups from which to choose.
	 * @return static
	 */
	public function setItems(array $items, bool $useKeys = true)
    {
        $this->rawItems = $useKeys ? $items :  array_combine($items, $items);
        return parent::setItems($items, $useKeys);
    }

    /**
     * @param array $itemObjects
     * @return static
     */
    public function setItemObjects(array $itemObjects, bool $usingGroups = false)
    {
        $this->rawItems = $itemObjects;

        if ($usingGroups) {
            // TODO:
            throw new \Nette\NotImplementedException("not implemented");
        }
        return parent::setItems(array_keys($itemObjects), false);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function setItemMapper(callable $callback)
    {
        $this->itemMapper = $callback;
        return $this;
    }

    public function getControl(): Html
    {
        // $opts = Helpers::buildSvelecteOptions($rawItems, $this->propertyMapping);
        parent::setItems([]);
        /** @var Html*/
        $select = parent::getControl();
        $select->removeAttributes(['class']);
        $select->removeChildren();
        $formattedOptions = $this->itemMapper
            ? call_user_func($this->itemMapper, $this->rawItems)
            : SvelecteHelper::simpleItemMapper($this->rawItems);

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
            $select->insert($i, "<option value='$val'>$val</option>", true);
        }
        $el->insert(null, $select);

        return $el;
    }

    /** ************************************ setters */

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
    /**
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
     * @param string $parentHtmlId
     * @return static
     */
    public function setParentSvelecte(string $parentHtmlId)
    {
        $this->svelecteParent = $parentHtmlId;
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
        $this->setSvelecteProp('placeholder', $prompt);

        return $this;
    }

    /**
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
}
