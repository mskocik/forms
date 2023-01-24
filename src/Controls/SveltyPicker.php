<?php declare(strict_types=1);

namespace Mskocik\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class SveltyPicker extends BaseControl
{
    public static string $element = 'el-picker';

	public static string $valueFormat = 'Y-m-d';

    private bool $valueAsDateTime = false;
    private ?\DateTimeZone $tz = null;

	private string $format = 'Y-m-d';

	private Html $wrap;

	public function __construct($label = null, ?string $format = null)
	{
		parent::__construct($label);
		$this->control->type = 'hidden';
		$this->wrap = Html::el(static::$element);
		if ($format) $this->format = $format;
	}

    /**
     * Set value
     *
     * @param string|\DateTimeInterface|null $value
     * @return static
     */
	public function setValue($value)
	{
		if ($value instanceof \DateTimeInterface) {
			$value = $value->format(static::$valueFormat);
		}
		$this->value = $value;
		return $this;
	}

    /**
     * Returns value. Depending on $valueAsDateTime property can return DateTimeImmutable instance.
     * Returns string by default
     * 
     * @return string|\DateTimeImmutable|null
     */
    public function getValue()
    {
        $rawValue = $this->value;
        if ($rawValue && ($this->valueAsDateTime || func_num_args() > 0)) {
            return \DateTimeImmutable::createFromFormat(static::$valueFormat, $rawValue, $this->tz);
        }
        return $rawValue ? $rawValue : null;
    }
    

	/** ************************************ svelty-picker API */

	/**
	 * Change date format, which is being display. Not the internal date representation
	 *
	 * @param string $format
	 * @return static
	 */
	public function setFormat(string $format)
	{
		$this->format = $format;
		return $this;
	}

    /**
     * One-way toggle if DateTime should be returned on getValue() call
     *
     * @param \DateTimeZone|null $dateTimeZone
     * @return static
     */
    public function useDateTime(?\DateTimeZone $dateTimeZone = null)
    {
        $this->valueAsDateTime = true;
        $this->tz = $dateTimeZone;
        return $this;
    }

    /** ************************************ internal control API */

	/**
	 * DatePicker structure is:
	 *
	 * <el-picker [format] [placeholder]>
	 *   <input type="hidden" name="$name" value>
	 *   <input type="text" name="$name_input" value>
	 * </el-picker>
	 *
	 * @return Html
	 */
	public function getControl()
	{
		$wrap = clone $this->wrap;
		$hidden = parent::getControl();
		$input = clone $hidden;
		$input->placeholder = $wrap->placeholder;
		$input->value = $this->value
		? \DateTime::createFromFormat(static::$valueFormat, $this->value)->format($this->format)
		: '';
		$input->type = 'text';
		$input->name = null;
		$input->id .= '_input';
        
        $hidden->removeAttribute('data-nette-rules');
		$hidden->value = $this->value;
		$wrap->value = $this->value;
		$wrap->setAttribute('format-type', 'php');
		$wrap->setAttribute('format', $this->format);
        $this->isRequired() && $wrap->setAttribute('required', 'required');
		$wrap->insert(0, $hidden);
		$wrap->insert(1, $input);

		return $wrap;
	}

	public function setHtmlAttribute(string $name, $value = true)
	{
		$this->wrap->$name = $value;
		if (
			$name === 'name'
			&& ($form = $this->getForm(false))
			&& !$this->isDisabled()
			&& $form->isAnchored()
			&& $form->isSubmitted()
		) {
			$this->loadHttpData();
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @param boolean $value
	 * @return static
	 */
	public function setInputHtmlAttribute(string $name, $value = true)
	{
		$this->control->$name = $value;
	}
}
