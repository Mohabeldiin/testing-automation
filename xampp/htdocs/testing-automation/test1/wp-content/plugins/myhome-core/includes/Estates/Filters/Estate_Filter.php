<?php

namespace MyHomeCore\Estates\Filters;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;

/**
 * Class Estate_Filter
 * @package MyHomeCore\Estates
 */
abstract class Estate_Filter {

	const PRICE = 'price';
	const TAXONOMY = 'tax_query';
	const POST_META = 'meta_query';
	const KEYWORD = 's';
	const ID = 'p';

	/**
	 * @var Attribute
	 */
	protected $attribute;

	/**
	 * @var Attribute_Value[]
	 */
	protected $values;

	/**
	 * @var string
	 */
	protected $compare;

	/**
	 * @return array
	 */
	public abstract function get_arg();

	/**
	 * @return string
	 */
	public abstract function get_type();

	/**
	 * Estate_Filter constructor.
	 *
	 * @param Attribute $attribute
	 * @param Attribute_Values $attribute_values
	 * @param string $compare
	 */
	public function __construct( Attribute $attribute, Attribute_Values $attribute_values, $compare = '=' ) {
		$this->attribute = $attribute;
		$this->values    = $attribute_values;
		$this->compare   = $compare;
	}

	/**
	 * @return Attribute
	 */
	public function get_attribute() {
		return $this->attribute;
	}

}