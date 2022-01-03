<?php

namespace MyHomeCore\Components\Contact_Form;


abstract class Contact_Form {

	abstract public function display();

	/**
	 * @return string
	 */
	public function get_label() {
		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-contact_form-label'] ) ) {
			$label = \MyHomeCore\My_Home_Core()->settings->props['mh-contact_form-label'];
		} else {
			$label = '';
		}
		$output_label = apply_filters( 'wpml_translate_single_string', $label, 'myhome-core', 'Contact form label' );

		return $output_label;
	}

}