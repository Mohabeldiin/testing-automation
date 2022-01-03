<?php

/**
 * Class Thim_Core_Admin.
 *
 * @package   Thim_Core
 * @since     0.1.0
 */
class Thim_Metabox extends Thim_Singleton {

	private static $saved_meta_boxes = false;

	/**
	 * Thim_Metabox constructor.
	 *
	 * @since 0.1.0
	 */
	protected function __construct() {

		if ( defined( 'LEARNPRESS_VERSION' ) && version_compare( LEARNPRESS_VERSION, '4.0.0-beta-0', '<' ) ) {
 			$this->init();
		} else {
			$this->includes();
 			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1000, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		}
	}

	/**
	 * Init.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		$this->libraries();
	}

	/**
	 * Hook register metabox.
	 *
	 * @since 0.1.0
	 */
	public function register_metabox() {
		do_action( 'thim_metabox_register' );
	}

	public function add_meta_boxes() {
		$metaboxes = apply_filters( 'rwmb_meta_boxes', array() );
		$metaboxes = apply_filters( 'thim_add_meta_boxes', $metaboxes );

		if ( ! empty( $metaboxes ) ) {
			foreach ( $metaboxes as $metabox ) {
				new Thim_Add_Meta_Box( $metabox );
			}
		}
	}

	public function save_meta_boxes( $post_id, $post ) {
		$post_id = absint( $post_id );

		if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
			return;
		}

		if ( empty( $_POST['thimpress_meta_box_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['thimpress_meta_box_nonce'] ), 'thimpress_save_meta_box' ) ) {
			return;
		}

		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		self::$saved_meta_boxes = true;

		$metaboxes = apply_filters( 'rwmb_meta_boxes', array() );
		$metaboxes = apply_filters( 'thim_add_meta_boxes', $metaboxes );

		if ( ! empty( $metaboxes ) ) {
			foreach ( $metaboxes as $metabox ) {
				$save = new Thim_Add_Meta_Box( $metabox );
				$save->save_meta_box( $metabox, $post_id );
			}
		}

		do_action( 'thim_core_save_metabox', $post_id, $post );
	}

	public function includes() {
		require_once THIM_CORE_ADMIN_PATH . '/function-meta-box.php';
	}

	public function enqueue_script() {
		wp_enqueue_script( 'thimpress_metabox', THIM_CORE_ADMIN_URI . '/assets/js/metabox.js', array( 'jquery', 'iris' ), THIM_CORE_VERSION );
	}

	/**
	 * Include libraries.
	 *
	 * @since 0.1.0
	 */
	private function libraries() {
		$this->metabox_core();
		$this->metabox_extensions();
	}

	/**
	 * Include metabox.io
	 *
	 * @since 0.1.0
	 */
	private function metabox_core() {
		if ( class_exists( 'RWMB_Loader' ) ) {
			return;
		}

		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box/meta-box.php';
	}

	/**
	 * Include metabox.io extensions.
	 *
	 * @since 0.1.0
	 */
	private function metabox_extensions() {
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-show-hide/meta-box-show-hide.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-group/meta-box-group.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-tabs/meta-box-tabs.php';
		require_once THIM_CORE_ADMIN_PATH . '/includes/meta-box-conditional-logic/meta-box-conditional-logic.php';
	}
}

class Thim_Add_Meta_Box {
	public $metabox;

	public function __construct( $metabox ) {
		$this->metabox = $metabox;

		$this->add_meta_box( $metabox );
	}

	public function add_meta_box( $metabox ) {
		$id        = isset( $metabox['id'] ) ? $metabox['id'] : sanitize_title( $metabox['title'] );
		$post_type = isset( $metabox['post_types'] ) ? $metabox['post_types'] : null;
		$context   = isset( $metabox['context'] ) ? $metabox['context'] : 'normal';
		$priority  = isset( $metabox['priority'] ) ? $metabox['priority'] : 'default';

		add_meta_box( $id, $metabox['title'], array( $this, 'callback_meta_box' ), $metabox['post_types'], $context, 'default' );
	}

	public function callback_meta_box() {
		$metabox = $this->metabox;

		wp_nonce_field( 'thimpress_save_meta_box', 'thimpress_meta_box_nonce' );

		if ( isset( $metabox['tabs'] ) ) {
			?>
			<div class="thimpress-metabox-tab">
				<div class="thimpress-metabox-tab__inner">
					<ul class="thimpress-metabox-tab__title">
						<?php foreach ( $metabox['tabs'] as $key => $tab ) : ?>
							<li class="thimpress-metabox-tab__li">
								<a href="#" data-content="<?php echo esc_attr( $key ); ?>">
									<i class="dashicons <?php echo $tab['icon']; ?>"></i>
									<?php echo $tab['label']; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>

					<div class="thimpress-metabox-tab__content">
						<?php foreach ( $metabox['tabs'] as $key => $tab ) : ?>
							<div class="thimpress-metabox-tab__content--inner" data-content="<?php echo esc_attr( $key ); ?>">
								<div class="thimpress-meta-box">
									<div class="thimpress-meta-box__inner">
										<?php
										if ( $metabox['fields'] ) {
											foreach ( $metabox['fields'] as $field ) {
												if ( isset( $field['tab'] ) && $field['tab'] == $key ) {
													$this->display( $field );
												}
											}
										}
										?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

		<?php } else { ?>
			<div class="thimpress-meta-box" data-show="<?php echo isset( $metabox['show'] ) ? htmlentities( wp_json_encode( $metabox['show'] ) ) : ''; ?>">
				<div class="thimpress-meta-box__inner">
					<?php
					if ( $metabox['fields'] ) {
						foreach ( $metabox['fields'] as $field ) {
							$this->display( $field );
						}
					}
					?>
				</div>
			</div>
			<?php
		}
	}

	public function save_meta_box( $meta_boxes, $post_id ) {
		if ( ! empty( $meta_boxes['fields'] ) ) {
			foreach ( $meta_boxes['fields'] as $setting ) {
				$field = $this->normalize_setting( $setting );

				switch ( $field['type'] ) {
					case 'text':
					case 'number':
					case 'textarea':
					case 'select':
					case 'image_select':
						$text = isset( $_POST[ $field['id'] ] ) ? wp_unslash( $_POST[ $field['id'] ] ) : '';
						update_post_meta( $post_id, $field['id'], $text );
						break;

					case 'checkbox':
						$checkbox = isset( $_POST[ $field['id'] ] ) ? 1 : 0;
						update_post_meta( $post_id, $field['id'], $checkbox );
						break;

					case 'duration':
						$duration = isset( $_POST[ $field['id'] ][0] ) && $_POST[ $field['id'] ][0] !== '' ? implode( ' ', wp_unslash( $_POST[ $field['id'] ] ) ) : '0 minute';
						update_post_meta( $post_id, $field['id'], $duration );
						break;

					case 'image_advanced':
						if ( isset( $field['max_file_uploads'] ) && $field['max_file_uploads'] > 1 ) {
							$image_advanced = isset( $_POST[ $field['id'] ] ) ? wp_unslash( array_filter( explode( ',', $_POST[ $field['id'] ] ) ) ) : array();

							$values     = get_post_meta( $post_id, $field['id'], false );

							$array_values = ! empty( $values ) ? array_values( $values ) : array();
							$co_values    = ! empty( $image_advanced ) ? array_values( $image_advanced ) : array();

							$del_val = array_diff( $array_values, $co_values );
							$new_val = array_diff( $co_values, $array_values );

							foreach ( $del_val as $level_id ) {
								delete_post_meta( $post_id, $field['id'], $level_id );
							}

							foreach ( $new_val as $level_id ) {
								add_post_meta( $post_id, $field['id'], $level_id, false );
							}
						} else {
							$image_advanced = isset( $_POST[ $field['id'] ] ) ? wp_unslash( $_POST[ $field['id'] ] ) : '';

							update_post_meta( $post_id, $field['id'], $image_advanced );
						}
						break;

					case 'group':
						if ( isset( $field['fields'] ) ) {
							$this->save_meta_box( $field, $post_id );
						}
						break;

					default:
						$default = isset( $_POST[ $field['id'] ] ) ? wp_unslash( $_POST[ $field['id'] ] ) : '';
						update_post_meta( $post_id, $field['id'], $default );
				}
			}
		}
	}

	public function display( $field ) {
		$field = $this->normalize_setting( $field );

		switch ( $field['type'] ) {
			case 'text':
			case 'number':
			case 'url':
				thim_meta_box_text_input_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'type'              => $field['type'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
					)
				);
				break;

			case 'textarea':
				thim_meta_box_textarea_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'checkbox':
				thim_meta_box_checkbox_field(
					array(
						'id'          => $field['id'],
						'label'       => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'description' => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'default'     => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'hidden'      => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'duration':
				thim_meta_box_duration_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default_time'      => $field['default_time'],
//						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'select':
				thim_meta_box_select_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'options'           => $field['options'],
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'select_advanced':
				thim_meta_box_select_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'options'           => $field['options'],
						'multiple'          => true,
						'wrapper_class'     => 'thim-select-2',
						'style'             => 'min-width: 200px',
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'image_advanced':
				thim_meta_box_file_input_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'multil'            => ( isset( $field['max_file_uploads'] ) && absint( $field['max_file_uploads'] ) > 1 ) ? true : false,
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'color':
				thim_meta_box_color_input_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'image_select':
				thim_meta_box_image_select_field(
					array(
						'id'                => $field['id'],
						'label'             => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'default'           => isset( $field['default'] ) ? $field['default'] : $field['std'],
						'description'       => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'options'           => isset( $field['options'] ) ? $field['options'] : false,
						'custom_attributes' => isset( $field['custom_attributes'] ) ? $field['custom_attributes'] : '',
						'hidden'            => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;

			case 'group':
				if ( isset( $field['fields'] ) ) {
					foreach ( $field['fields'] as $g_fields ) {
						$this->display( $g_fields );
					}
				}
				break;

			case 'wysiwyg':
				thim_meta_box_wysiwyg_field(
					array(
						'id'          => $field['id'],
						'label'       => isset( $field['label'] ) ? $field['label'] : $field['name'],
						'description' => isset( $field['description'] ) ? $field['description'] : $field['desc'],
						'type'        => $field['type'],
						'hidden'      => isset( $field['hidden'] ) ? $field['hidden'] : false,
					)
				);
				break;
		}
	}

	public function normalize_setting( $setting ) {
		$setting = wp_parse_args(
			$setting,
			array(
				'id'   => '',
				'name' => '',
				'desc' => '',
				'std'  => '',
			)
		);

		return $setting;
	}
}
