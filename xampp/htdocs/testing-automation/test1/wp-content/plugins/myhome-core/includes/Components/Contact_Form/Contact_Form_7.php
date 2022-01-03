<?php

namespace MyHomeCore\Components\Contact_Form;


/**
 * Class Contact_Form_7
 * @package MyHomeCore\Components\Contact_Form
 */
class Contact_Form_7 extends Contact_Form {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * Contact_Form_7 constructor.
	 *
	 * @param string $title
	 * @param int $id
	 */
	public function __construct( $title, $id ) {
		$this->title = $title;
		$this->id    = $id;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function get_ID() {
		return $this->id;
	}

	public function display() {
		ob_start();
		?>
        [contact-form-7 id="<?php echo esc_attr( $this->get_ID() ); ?>" title="<?php echo esc_attr( $this->get_ID() ); ?>"]
		<?php
		echo do_shortcode( ob_get_clean() );
	}

	/**
	 * @return Contact_Form_7[]
	 */
	public static function get_forms() {
	    global $wpdb;
	    $posts = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'wpcf7_contact_form' ORDER BY post_title");

	    $forms = [];
		foreach ( $posts as $contact_form ) {
			$forms[] = new Contact_Form_7( $contact_form->post_title, $contact_form->ID );
		}

		return $forms;
	}

	/**
	 * @return array
	 */
	public static function get_forms_list() {
		$list  = array();
		$forms = self::get_forms();
		foreach ( $forms as $form ) {
			$list[ $form->get_ID() ] = $form->get_title();
		}

		return $list;
	}

	/**
	 * @param int $contact_form_id
	 *
	 * @return Contact_Form
	 * @throws \ErrorException
	 */
	public static function get_by_ID( $contact_form_id ) {
		$post = get_post( $contact_form_id );
		if ( ! $post instanceof \WP_Post ) {
			throw new \ErrorException( 'Contact form with ID ' . $contact_form_id . ' not found.' );
		}

		return new Contact_Form_7( $post->post_title, $post->ID );
	}

}