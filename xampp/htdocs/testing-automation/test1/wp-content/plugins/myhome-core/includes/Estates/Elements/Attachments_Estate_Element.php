<?php

namespace MyHomeCore\Estates\Elements;

/**
 * Class Attachments_Estate_Element
 * @package MyHomeCore\Estates\Elements
 */
class Attachments_Estate_Element extends Estate_Element {

	/**
	 * @return string
	 */
	protected function get_template_name() {
		return self::ATTACHMENTS;
	}

	/**
	 * @return bool
	 */
	public function has_attachments() {
		return $this->estate->has_attachments();
	}

	/**
	 * @return \MyHomeCore\Common\Attachment[]
	 */
	public function get_attachments() {
		return $this->estate->get_attachments();
	}

}