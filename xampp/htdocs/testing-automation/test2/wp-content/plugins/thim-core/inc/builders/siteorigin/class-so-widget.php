<?php
/**
 * Thim_Builder SiteOrigin widget class
 *
 * @version     1.0.0
 * @author      ThimPress
 * @package     Thim_Builder/Classes
 * @category    Classes
 * @author      Thimpress, leehld
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Thim_Builder_SO_Widget' ) ) {
	/**
	 * Class Thim_Builder_SO_Widget
	 */
	abstract class Thim_Builder_SO_Widget extends WP_Widget {

		/**
		 * @var array
		 */
		protected $form_options;

		/**
		 * @var string
		 */
		protected $base_folder;

		/**
		 * @var array
		 */
		protected $repeater_html;

		/**
		 * @var array
		 */
		protected $field_ids;

		/**
		 * @var
		 */
		protected $current_instance;

		/**
		 * @var null
		 */
		protected $config_class = null;

		/**
		 * @var int|mixed|string
		 */
		protected $group;

		/**
		 * @var string
		 */
		protected $base = '';
		/**
		 * @var string
		 */
		protected $icon = '';
		/**
		 * @var string
		 */
		protected $template_name = '';

		/**
		 * @var string
		 */
		protected $assets_url = '';

		/**
		 * @var string
		 */
		protected $assets_path = '';

		/**
		 * Thim_Builder_SO_Widget constructor.
		 */
		public function __construct() {

			if ( ! class_exists( $this->config_class ) ) {
				return;
			}

			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			$this->base = $config_class::$base;
			$name       = $config_class::$name;
			//  fix base of shortcode for theme
			$this->template_name = $config_class::$template_name;

			$widget_options = array(
				'description'   => $config_class::$desc,
				'panels_groups' => array( 'thim_builder_so_widgets' ),
				'panels_icon'   => $config_class::$icon ? $config_class::$icon : ''
			);

			$options = $this->get_config_options();

			$this->form_options = Thim_Builder_SO_Mapping::mapping_options( $options );

			// group
			$this->group = $config_class::$group;

			// assets
			$this->assets_url  = $config_class::$assets_url;
			$this->assets_path = $config_class::$assets_path;

			$this->base_folder = TP_THEME_ELEMENTS_THIM_DIR . "general/$this->base";

			$this->repeater_html = array();
			$this->field_ids     = array();

 			$control_options = wp_parse_args(
				$widget_options, array(
					'width' => 600,
				)
			);

			// enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_scripts' ) );

			parent::__construct( $this->base, $name, $widget_options, $control_options );
		}

		/**
		 * Get the form options and allow child widgets to modify that form.
		 *
		 * @return mixed
		 */
		public function form_options() {
			return $this->form_options;
		}


		/**
		 * @param null  $widget_options
		 * @param array $atts
		 *
		 * @return array
		 */
		public function get_options( $widget_options = null, $atts = array() ) {
			if ( ! $widget_options ) {
				$widget_options = $this->form_options;
			}

			if ( ! is_array( $widget_options ) || ! $widget_options ) {
				return array();
			}

			$options = array();
			foreach ( $widget_options as $key => $option ) {

				if ( in_array( $option['type'], array( 'repeater', 'section' ) ) ) {
					$options[$key] = $this->get_options( $option['fields'] );
				} else {
					if ( $atts ) {
						foreach ( $atts as $name_att => $value ) {
							if ( $key == $name_att ) {
								$option[$key] = $value;
								unset( $atts[$key] );
							} else {
								$options[$key] = isset( $option['default'] ) ? $option['default'] : '';
							}
						}
					} else {
						$options[$key] = isset( $option['default'] ) ? $option['default'] : '';
					}
				}
			}

			return $options;
		}

		/**
		 * Display the widget.
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

  			$this->current_instance = $instance;

			$args = wp_parse_args(
				$args, array(
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '',
					'after_title'   => '',
				)
			);

			// Add any missing default values to the instance

			$instance = $this->add_defaults( $instance, $this->form_options );

			thim_builder_widget_add_inline_css(  $this->global_widget_css_inline() );

			// enqueue frontend scripts
			$this->enqueue_frontend_scripts();

			// sync variable from builders
			$params = $this->_handle_variables( $instance );

			// allow hook before template
			do_action( 'thim-builder/before-element-template', $this->id_base );

			// include template
			echo ent2ncr( $args['before_widget'] );

			// fix for old themes by tuanta
 			$_params  = thim_builder_folder_group() ? 'params' : 'instance';
			$group_folder  = thim_builder_folder_group() ? $this->group.'/' : '';



			$base_file = $this->template_name ? $this->template_name : $this->base;

			echo '<div class="thim-widget-' . $this->base . ' template-' . $base_file . '">';
			thim_builder_get_template(
				$base_file, array(
				$_params   => $params,
				'style_so' => isset( $instance['style'] ) ? $instance['style'] : '',
				'args'     => $args
			), $group_folder . $this->base . '/tpl/'
			);

			echo '</div>';

			echo ent2ncr( $args['after_widget'] );
		}

		/**
		 * @param $instance
		 *
		 * @return mixed
		 */
		public function _handle_variables( $instance ) {

			$instance = array_merge(
				$instance, array(
					'base'          => $this->base,
					'group'         => $this->group,
					'template_path' => $this->group . '/' . $this->base . '/tpl/'
				)
			);

			do_action( 'thim-builder/so/handle-variables', $instance );

			return $instance;
		}

		/**
		 * By default, just return an array. Should be overwritten by child widgets.
		 *
		 * @param $instance
		 * @param $args
		 *
		 * @return array
		 */
		public function get_template_variables( $instance, $args ) {
			return array();
		}

		/**
		 * Add default values to the instance.
		 *
		 * @param     $instance
		 * @param     $options
		 *
		 * @return mixed
		 */
		function add_defaults( $instance, $options ) {
			foreach ( $options as $id => $field ) {
				if ( $field['type'] == 'repeater' && ! empty( $instance[$id] ) ) {

				} else {
					if ( ! isset( $instance[$id] ) && isset( $field['default'] ) ) {
						$instance[$id] = $field['default'];
					}
				}
			}

			return $instance;
		}

		/**
		 * Display the widget form.
		 *
		 * @param array $instance
		 *
		 * @return string|void
		 */
		public function form( $instance ) {
			$this->backend_scripts();

			$form_id    = 'thim_widget_form_' . md5( uniqid( rand(), true ) );
			$class_name = str_replace( '_', '-', strtolower( get_class( $this ) ) );
			?>
			<div
				class="thim-widget-form thim-widget-form-main thim-widget-form-main-<?php echo esc_attr( $class_name ) ?>"
				id="<?php echo esc_attr( $form_id ) ?>" data-class="<?php echo get_class( $this ) ?>">
				<?php
				foreach ( $this->form_options as $field_name => $field ) {
					$this->render_field(
						$field_name,
						$field,
						isset( $instance[$field_name] ) ? $instance[$field_name] : null,
						false
					);
				}
				?>
			</div>

			<?php if ( ! empty( $this->widget_options['help'] ) ) : ?>
				<a href="<?php echo esc_url( $this->widget_options['help'] ) ?>"
				   class="thim-widget-help-link thim-panels-help-link"
				   target="_blank"><?php esc_attr_e( 'Help', 'thim-core' ) ?></a>
			<?php endif; ?>

			<script type="text/javascript">
				(function ($) {
					if (typeof window.ob_repeater_html === 'undefined')
						window.ob_repeater_html = {};
					window.ob_repeater_html["<?php echo get_class( $this ) ?>"] = <?php echo json_encode( $this->repeater_html ) ?>;
					if (typeof $.fn.obSetupForm !== 'undefined') {
						$('#<?php echo esc_attr( $form_id ) ?>').obSetupForm();
					} else {
						// Init once admin scripts have been loaded
						$(window).load(function () {
							$('#<?php echo esc_attr( $form_id ) ?>').obSetupForm();
						});
					}
				})(jQuery);
			</script>
			<?php
		}

		/**
		 * Enqueue the admin scripts for the widget form.
		 */
		function backend_scripts() {

			if ( ! wp_script_is( 'thim-widget-admin' ) ) {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'thim-widget-admin', THIM_CORE_ADMIN_URI . '/assets/css/widget-admin.css', array( 'media-views' ), THIM_CORE_VERSION );
				wp_enqueue_style( 'thim-font-awesome' );

				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_media();

				wp_enqueue_script(
					'thim-widget-admin', THIM_CORE_ADMIN_URI . '/assets/js/widget-admin.min.js', array(
					'jquery',
					'jquery-ui-sortable',
					'editor'
				), THIM_CORE_VERSION, true
				);

				wp_localize_script(
					'thim-widget-admin', 'soWidgets', array(
						'ajaxurl' => wp_nonce_url( admin_url( 'admin-ajax.php' ), 'widgets_action', '_widgets_nonce' ),
						'sure'    => __( 'Are you sure?', 'thim-core' )
					)
				);
			}
		}

		/**
		 * Update the widget instance.
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$new_instance = $this->sanitize( $new_instance, $this->form_options );

			return $new_instance;
		}

		/**
		 * @param      $instance
		 * @param bool $options
		 *
		 * @return mixed
		 */
		public function sanitize( $instance, $options = false ) {

			if ( ! $options ) {
				$options = $this->form_options;
			}

			foreach ( $options as $name => $option ) {

				if ( empty( $instance[$name] ) ) {
					$instance[$name] = false;
				}

				switch ( $option['type'] ) {
					case 'radio' :
						$keys = array_keys( $option['options'] );
						if ( ! in_array( $instance[$name], $keys ) ) {
							$instance[$name] = isset( $option['default'] ) ? $option['default'] : false;
						}
						break;

					case 'number' :
					case 'slider':
						$instance[$name] = (float) $instance[$name];
						break;

					case 'text' :
					case 'textarea':
						if ( empty( $option['allow_html_formatting'] ) ) {
							$instance[$name] = sanitize_text_field( $instance[$name] );
						} else {
							$instance[$name] = wp_kses( $instance[$name], $option['allow_html_formatting'] );
						}
						break;

					case 'textarea_raw_html':
						$instance[$name] = $instance[$name];
						break;


					case 'color':
						//						if ( ! preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $instance[ $name ] ) ) {
						//							$instance[ $name ] = false;
						//						}
						break;

					case 'media' :
						$instance[$name] = intval( $instance[$name] );
						break;

					case 'checkbox':
						$instance[$name] = ! empty( $instance[$name] );
						break;

					case 'widget':
						if ( ! empty( $option['class'] ) && class_exists( $option['class'] ) ) {
							/**
							 * @var $widget_class WP_Widget
							 */
							$widget_class = new $option['class'];

							if ( is_a( $widget_class, 'SiteOrigin_Widget' ) ) {
								$instance[$name] = $widget_class->update( $instance[$name], $instance[$name] );
							}
						}
						break;

					case 'repeater':
						if ( ! empty( $instance[$name] ) ) {
							foreach ( $instance[$name] as $i => $sub_instance ) {
								$instance[$name][$i] = $this->sanitize( $sub_instance, $option['fields'] );
							}
						}
						break;

					case 'section':
						$instance[$name] = $this->sanitize( $instance[$name], $option['fields'] );
						break;

					case 'so_link':
						$instance[$name] = $instance[$name] ? json_decode( $instance[$name], true ) : '';
						break;

					default:
						$instance[$name] = sanitize_text_field( $instance[$name] );
						break;
				}

				if ( isset( $option['sanitize'] ) ) {
					// This field also needs some custom sanitization
					switch ( $option['sanitize'] ) {
						case 'url':
							$instance[$name] = esc_url_raw( $instance[$name] );
							break;

						case 'email':
							$instance[$name] = sanitize_email( $instance[$name] );
							break;
					}
				}
			}

			return $instance;
		}

		/**
		 * @param        $field_name
		 * @param array  $repeater
		 * @param string $repeater_append
		 *
		 * @return mixed|string
		 */
		public function so_get_field_name( $field_name, $repeater = array(), $repeater_append = '[]' ) {
			if ( empty( $repeater ) ) {
				return $this->get_field_name( $field_name );
			} else {

				$repeater_extras = '';
				foreach ( $repeater as $r ) {
					$repeater_extras .= '[' . $r['name'] . ']';
					if ( isset( $r['type'] ) && $r['type'] === 'repeater' ) {
						$repeater_extras .= '[#' . $r['name'] . '#]';
					}
				}

				$name = $this->get_field_name( '{{{FIELD_NAME}}}' );

				$name = str_replace( '[{{{FIELD_NAME}}}]', $repeater_extras . '[' . esc_attr( $field_name ) . ']', $name );

				return $name;
			}
		}

		/**
		 * Get the ID of this field.
		 *
		 * @param         $field_name
		 * @param array   $repeater
		 * @param boolean $is_template
		 *
		 * @return string
		 */
		public function so_get_field_id( $field_name, $repeater = array(), $is_template = false ) {
			if ( empty( $repeater ) ) {
				return $this->get_field_id( $field_name );
			} else {
				// $name          = $repeater; fix
				$name = array();
				foreach ( $repeater as $key => $val ) {
					$name[] = $val['name'];
				}
				$name[]        = $field_name;
				$field_id_base = $this->get_field_id( implode( '-', $name ) );
				if ( $is_template ) {
					return $field_id_base . '-{id}';
				}
				if ( ! isset( $this->field_ids[$field_id_base] ) ) {
					$this->field_ids[$field_id_base] = 1;
				}
				$curId = $this->field_ids[$field_id_base] ++;

				return $field_id_base . '-' . $curId;
			}
		}

		/**
		 * Render a form field
		 *
		 * @param       $name
		 * @param       $field
		 * @param       $value
		 * @param array $repeater
		 */
		function render_field( $name, $field, $value, $repeater = array(), $is_template = false ) {
			if ( is_null( $value ) && isset( $field['default'] ) ) {
				$value = $field['default'];
			}
			$extra_class = '';
			if ( ! empty( $field['class'] ) ) {
				$extra_class = $field['class'];
			}
			$wrapper_attributes = array(
				'class' => array(
					'thim-widget-field',
					'thim-widget-field-type-' . $field['type'],
					'thim-widget-field-' . $name,
					$extra_class
				)
			);

			if ( ! empty( $field['state_name'] ) ) {
				$wrapper_attributes['class'][] = 'thim-widget-field-state-' . $field['state_name'];
			}
			if ( ! empty( $field['hidden'] ) ) {
				$wrapper_attributes['class'][] = 'thim-widget-field-is-hidden';
			}
			if ( ! empty( $field['optional'] ) ) {
				$wrapper_attributes['class'][] = 'thim-widget-field-is-optional';
			}
			$wrapper_attributes['class'] = implode( ' ', array_map( 'sanitize_html_class', $wrapper_attributes['class'] ) );

			if ( ! empty( $field['state_emitter'] ) ) {
				// State emitters create new states for the form
				$wrapper_attributes['data-state-emitter'] = json_encode( $field['state_emitter'] );
			}

			if ( ! empty( $field['state_handler'] ) ) {
				// State handlers decide what to do with form states
				$wrapper_attributes['data-state-handler'] = json_encode( $field['state_handler'] );
			}

			if ( ! empty( $field['state_handler_initial'] ) ) {
				// Initial state handlers are only run when the form is first loaded
				$wrapper_attributes['data-state-handler-initial'] = json_encode( $field['state_handler_initial'] );
			}

			?>
			<div <?php foreach ( $wrapper_attributes as $attr => $attr_val ) {
				echo ent2ncr( $attr . '="' . esc_attr( $attr_val ) . '" ' );
			} ?>><?php

			$field_id = $this->so_get_field_id( $name, $repeater, $is_template );

			if ( $field['type'] != 'repeater' && $field['type'] != 'checkbox' && $field['type'] != 'separator' && ! empty( $field['label'] ) ) {
				?>
				<label for="<?php echo esc_attr( $field_id ) ?>"
					   class="thim-widget-field-label <?php if ( empty( $field['hide'] ) ) {
						   echo 'thim-widget-section-visible';
					   } ?>">
					<?php
					echo ent2ncr( $field['label'] );
					if ( ! empty( $field['optional'] ) ) {
						echo ' <span class="field-optional">(' . __( 'Optional', 'thim-core' ) . ')</span>';
					}
					?>
				</label>
				<?php
			}

			/**
			 * Custom field type
			 */
			$custom_filed_type = apply_filters(
				'tp_widget_custom_field_type', array(
				'name'       => $name,
				'field'      => $field,
				'value'      => $value,
				'repeater'   => $repeater,
				'field_id'   => $field_id,
				'field_name' => $this->so_get_field_name( $name, $repeater )
			), array()
			);

			$custom_filed_type_sanitize = array();

			if ( is_array( $custom_filed_type ) && count( $custom_filed_type ) > 0 ) {
				foreach ( $custom_filed_type as $index => $field_type ) {
					if ( is_array( $field_type ) && array_key_exists( 'type', $field_type ) && array_key_exists( 'form', $field_type ) ) {
						$custom_filed_type_sanitize[] = $field_type;
					}
				}
			}

			switch ( $field['type'] ) {
				case 'text' :
					?>
					<input type="text" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   id="<?php echo esc_attr( $this->so_get_field_id( $name, $repeater ) ) ?>"
						   value="<?php echo esc_attr( $value ) ?>" class="widefat thim-widget-input" /><?php
					break;

				case 'color' :
					?>
					<input type="text" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( $value ) ?>"
						   class="widefat thim-widget-input thim-widget-input-color color-picker"
						   data-alpha="true" /><?php
					break;

				case 'number' :
					?>
					<input type="number" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   id="<?php echo esc_attr( $this->so_get_field_id( $name, $repeater ) ) ?>"
						   value="<?php echo esc_attr( $value ) ?>"
						   class="widefat thim-widget-input thim-widget-input-number" /><?php
					if ( ! empty( $field['suffix'] ) ) {
						echo ' (' . $field['suffix'] . ') ';
					}
					break;

				case 'radioimage' :
					foreach ( $field['options'] as $key => $imageURL ) {
						// Get the correct value, we might get a blank if index / value is 0
						if ( $value == '' ) {
							$value = $key;
						}
						?>
						<label class='tp-radio-image'>
							<input class="ob-widget-input" type="radio"
								   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
								   value="<?php echo esc_attr( $key ) ?>" <?php
							checked( $value, $key );
							?>/>
							<img src="<?php echo esc_attr( $imageURL ) ?>"/>
						</label>
						<?php
					}
					break;

				case 'textarea' :
				case 'textarea_raw_html' :
					$this->so_get_field_name( $name, $repeater );
					?>
					<textarea type="text"
							  name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater, $is_template ) ) ?>"
							  id="<?php echo esc_attr( $this->so_get_field_id( $name, $repeater ) ) ?>"
							  class="widefat thim-widget-input"
							  rows="<?php echo ! empty( $field['rows'] ) ? intval( $field['rows'] ) : 4 ?>"><?php echo esc_textarea( $value ) ?></textarea><?php
					break;

				case 'extra_textarea' :
					$param_value = str_replace( ",", "\n", esc_textarea( $value ) );
					?>
					<textarea class="widefat" id="<?php echo esc_attr( $this->so_get_field_id( $name, $repeater ) ) ?>"
							  name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							  rows="6"><?php echo ent2ncr( $param_value ); ?></textarea>
					<?php
					break;

				case 'editor' :
					// The editor field doesn't actually work yet, this is just a placeholder
					?>
					<textarea type="text" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							  id="<?php echo esc_attr( $this->so_get_field_id( $name, $repeater ) ) ?>"
							  class="widefat thim-widget-input thim-widget-input-editor"
							  rows="<?php echo ! empty( $field['rows'] ) ? intval( $field['rows'] ) : 4 ?>"><?php echo esc_textarea( $value ) ?></textarea><?php
					break;

				case 'slider':
					?>
					<div class="thim-widget-slider-value"><?php echo ! empty( $value ) ? $value : 0 ?></div>
					<div class="thim-widget-slider-wrapper">
						<div class="thim-widget-value-slider"></div>
					</div>
					<input
						type="number"
						name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						id="<?php echo esc_attr( $field_id ) ?>"
						value="<?php echo ! empty( $value ) ? esc_attr( $value ) : 0 ?>"
						min="<?php echo isset( $field['min'] ) ? intval( $field['min'] ) : 0 ?>"
						max="<?php echo isset( $field['max'] ) ? intval( $field['max'] ) : 100 ?>"
						data-integer="<?php echo ! empty( $field['integer'] ) ? 'true' : 'false' ?>"/>
					<?php
					break;

				case 'select':
					?>
					<select name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							id="<?php echo esc_attr( $field_id ) ?>"
							class="thim-widget-input thim-widget-select ob-widget-input" <?php echo ! empty( $field['multiple'] ) ? "multiple" : ""; ?>>
						<?php
						if ( isset( $field['prompt'] ) ) {
							?>
							<option value="default" disabled="disabled"
									selected="selected"><?php echo esc_html( $field['prompt'] ) ?></option>
							<?php
						}
						?>
						<?php foreach ( $field['options'] as $key => $val ) : ?>
							<?php
							if ( is_array( $value ) ) {
								$selected = selected( true, in_array( $key, $value ), false );
							} else {
								$selected = selected( $key, $value, false );
							}
							?>
							<option
								value="<?php echo esc_attr( $key ) ?>" <?php echo $selected; ?>><?php echo esc_html( $val ) ?></option>
						<?php endforeach; ?>
					</select>
					<?php
					break;

				case 'checkbox':
					?>
					<label for="<?php echo esc_attr( $field_id ) ?>">
						<input type="checkbox"
							   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							   id="<?php echo esc_attr( $field_id ) ?>"
							   class="thim-widget-input" <?php checked( ! empty( $value ) ) ?> />
						<?php echo ent2ncr( $field['label'] ) ?>
					</label>
					<?php
					break;

				case 'radio':
					?>
					<?php if ( ! isset( $field['options'] ) || empty( $field['options'] ) ) {
					return;
				} ?>
					<?php foreach ( $field['options'] as $k => $v ) : ?>
					<label for="<?php echo esc_attr( $field_id ) . '-' . $k ?>">
						<input type="radio"
							   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							   id="<?php echo esc_attr( $field_id ) . '-' . $k ?>" class="thim-widget-input"
							   value="<?php echo esc_attr( $k ) ?>" <?php checked( $k, $value ) ?>> <?php echo esc_html( $v ) ?>
					</label>
				<?php endforeach; ?>
					<?php
					break;

				case 'media':
					if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
						printf( __( 'You need to <a href="%s">upgrade</a> to WordPress 3.5 to use media fields', 'thim-core' ), admin_url( 'update-core.php' ) );
						break;
					}
					if ( ! empty( $value ) ) {
						if ( is_array( $value ) ) {
							$src = $value;
						} else {
							$post = get_post( $value );
							$src  = wp_get_attachment_image_src( $value, 'thumbnail' );
							if ( empty( $src ) ) {
								$src = wp_get_attachment_image_src( $value, 'thumbnail', true );
							}
						}
					} else {
						$src = array( '', 0, 0 );
					}

					$choose_title  = empty( $args['choose'] ) ? __( 'Choose Media', 'thim-core' ) : $args['choose'];
					$update_button = empty( $args['update'] ) ? __( 'Set Media', 'thim-core' ) : $args['update'];
					$library       = empty( $field['library'] ) ? 'image' : $field['library'];
					?>
					<div class="media-field-wrapper">
						<div class="current">
							<div class="thumbnail-wrapper">
								<img src="<?php echo esc_url( $src[0] ) ?>" class="thumbnail"
									<?php if ( empty( $src[0] ) ) {
										echo "style='display:none'";
									} ?> />
							</div>
							<div class="title"><?php if ( ! empty( $post ) ) {
									echo esc_attr( $post->post_title );
								} ?></div>
						</div>
						<a href="#" class="media-upload-button" data-choose="<?php echo esc_attr( $choose_title ) ?>"
						   data-update="<?php echo esc_attr( $update_button ) ?>"
						   data-library="<?php echo esc_attr( $library ) ?>">
							<?php echo esc_html( $choose_title ) ?>
						</a>

						<a href="#" class="media-remove-button"><?php esc_attr_e( 'Remove', 'thim-core' ) ?></a>
					</div>

					<input type="hidden" value="<?php echo esc_attr( is_array( $value ) ? '-1' : $value ) ?>"
						   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   class="thim-widget-input"/>
					<div class="clear"></div>
					<?php
					break;

				case 'multimedia':
					if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
						printf( __( 'You need to <a href="%s">upgrade</a> to WordPress 3.5 to use media fields', 'thim-core' ), admin_url( 'update-core.php' ) );
						break;
					}

					$data_img = "";
					if ( $value ) {
						$data = explode( ",", $value );
						if ( is_array( $data ) ) {
							foreach ( $data as $v ) {
								$post = get_post( $v );
								$src  = wp_get_attachment_image_src( $v, 'thumbnail' );
								if ( empty( $src ) ) {
									$src = wp_get_attachment_image_src( $v, 'thumbnail', true );
								}
								$data_img .= '<li id ="' . $v . '" class="current">
							<div class="thumbnail-wrapper">
								<img src="' . esc_url( $src[0] ) . '" class="thumbnail"/>
								<a href="#" class="multimedia-remove-button">x</a>
							</div>
						</li> ';
							}
						} else {
							$post = get_post( $data );
							$src  = wp_get_attachment_image_src( $data, 'thumbnail' );
							if ( empty( $src ) ) {
								$src = wp_get_attachment_image_src( $data, 'thumbnail', true );
							}
							$data_img .= '<li class="current">
							<div class="thumbnail-wrapper">
								<img src="' . esc_url( $src[0] ) . '" class="thumbnail"/>
								<a href="#" class="multimedia-remove-button">x</a>
							</div>
						</li> ';
						}

					} else {
						$data_img = "";
					}

					$choose_title  = empty( $args['choose'] ) ? __( 'Choose Media', 'thim-core' ) : $args['choose'];
					$update_button = empty( $args['update'] ) ? __( 'Set Media', 'thim-core' ) : $args['update'];
					$library       = empty( $field['library'] ) ? 'image' : $field['library'];
					?>
					<div class="multi-media-field-wrapper">

						<ul class="media-content">
							<?php echo ent2ncr( $data_img ); ?>
						</ul>
						<a href="#" class="media-upload-button" data-choose="<?php echo esc_attr( $choose_title ) ?>"
						   data-update="<?php echo esc_attr( $update_button ) ?>"
						   data-library="<?php echo esc_attr( $library ) ?>">
							<?php echo esc_html( $choose_title ) ?>
						</a>

					</div>

					<input type="hidden" value="<?php echo esc_attr( is_array( $value ) ? '-1' : $value ) ?>"
						   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   class="thim-widget-input"/>
					<div class="clear"></div>
					<?php
					break;

				case 'posts' :
					?>
					<input type="hidden" value="<?php echo esc_attr( is_array( $value ) ? '' : $value ) ?>"
						   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   class="thim-widget-input"/>
					<a href="#" class="ob-select-posts button button-secondary">
						<span
							class="ob-current-count"><?php echo thim_widget_post_selector_count_posts( is_array( $value ) ? '' : $value ) ?></span>
						<?php esc_attr_e( 'Build Posts Query', 'thim-core' ) ?>
					</a>
					<?php
					break;

				case 'repeater':
					if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
						return;
					}

					if ( ! $repeater ) {
						$repeater = array();
					}
					$repeater[] = array( 'name' => $name, 'type' => 'repeater' ); // instead of $repeater[] = $name
					$html       = array();
					foreach ( $field['fields'] as $sub_field_name => $sub_field ) {
						ob_start();
						$this->render_field(
							$sub_field_name,
							$sub_field,
							isset( $value[$sub_field_name] ) ? $value[$sub_field_name] : null,
							$repeater,
							true
						);
						$html[] = ob_get_clean();
					}

					$this->repeater_html[$name] = implode( '', $html );

					$item_label = isset( $field['item_label'] ) ? $field['item_label'] : null;
					if ( ! empty( $item_label ) ) {
						$item_label = json_encode( $item_label );
					}
					$item_name = ! empty( $field['item_name'] ) ? $field['item_name'] : __( 'Item', 'thim-widgets' );
					?>
					<div class="thim-widget-field-repeater"
						 data-item-name="<?php echo esc_attr( $field['item_name'] ) ?>"
						 data-repeater-name="<?php echo esc_attr( $name ) ?>" <?php echo ! empty( $item_label ) ? 'data-item-label="' . esc_attr( $item_label ) . '"' : '' ?>>
						<div class="thim-widget-field-repeater-top">
							<div class="thim-widget-field-repeater-expend"></div>
							<h3><?php echo ent2ncr( $field['label'] ) ?></h3>
						</div>
						<div class="thim-widget-field-repeater-items">
							<?php
							if ( ! empty( $value ) ) {
								foreach ( $value as $v ) {
									?>
									<div class="thim-widget-field-repeater-item ui-draggable">
										<div class="thim-widget-field-repeater-item-top">
											<div class="thim-widget-field-expand"></div>
											<div class="thim-widget-field-remove"></div>
											<h4><?php echo esc_html( $field['item_name'] ) ?></h4>
										</div>
										<div class="thim-widget-field-repeater-item-form">
											<?php
											foreach ( $field['fields'] as $sub_field_name => $sub_field ) {
												$this->render_field(
													$sub_field_name,
													$sub_field,
													isset( $v[$sub_field_name] ) ? $v[$sub_field_name] : null,
													$repeater
												);
											}
											?>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
						<div class="thim-widget-field-repeater-add"><?php esc_attr_e( 'Add', 'thim-core' ) ?></div>
					</div>
					<?php
					break;

				case 'widget' :
					// Create the extra form entries
					$sub_widget = new $field['class'];
					?>
					<div class="thim-widget-section <?php if ( ! empty( $field['hide'] ) ) {
					echo 'thim-widget-section-hide';
				} ?>"><?php
					$new   = $repeater;
					$new[] = array( 'name' => $name );
					foreach ( $sub_widget->form_options as $sub_name => $sub_field ) {
						$this->render_field(
							$sub_name,
							$sub_field,
							isset( $value[$sub_name] ) ? $value[$sub_name] : null,
							$new
						);
					}
					?></div><?php
					break;

				case 'section' :
					?>
					<div class="thim-widget-section <?php if ( ! empty( $field['hide'] ) ) {
					echo 'thim-widget-section-hide';
				} ?>"><?php
					if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
						return;
					}

					foreach ( (array) $field['fields'] as $sub_name => $sub_field ) {
						$new   = $repeater;
						$new[] = array( 'name' => $name );
						$this->render_field(
							$sub_name,
							$sub_field,
							isset( $value[$sub_name] ) ? $value[$sub_name] : null,
							$new,
							false
						);
					}
					?></div><?php
					break;

				case 'bucket' :
					// A bucket select and explore field
					?>
					<input type="text" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( $value ) ?>"
						   class="widefat thim-widget-input" /><?php
					break;

				case 'so_link':
					$link = json_decode( $value );

					$title  = isset( $link->title ) ? $link->title : '';
					$url    = isset( $link->url ) ? $link->url : '';
					$target = isset( $link->target ) ? $link->target : '';
					$rel    = isset( $link->rel ) ? $link->rel : '';
					?>
					<div class="select-link-popup-button">
						<input type="hidden"
							   name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
							   id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( $value ) ?>"
							   data-json="<?php echo esc_attr( $value ); ?>" class="widefat thim-widget-input"/>
						<a href="#" class="button"><?php _e( 'Select URL', 'thim-core' ); ?></a>
						<span class="link-title-label"><?php _e( 'Title: ', 'thim-core' ); ?></span>
						<span class="link-title"><?php echo esc_html( $title ); ?></span>
						<span class="link-url-label"><?php _e( 'URL: ', 'thim-core' ); ?></span>
						<span
							class="link-url"><?php echo esc_url( $url ) . ' ' . esc_attr( $target ) . ' ' . esc_attr( $rel ); ?></span>
					</div>
					<?php
					break;
				case 'icon' :
					$prefix_icon = '';
					if ( isset( $field['settings'] ) && ! empty( $field['settings'] ) ) {
						$icons = apply_filters( 'thim-builder-so-' . $field['settings']['type'] . '-icon', array() );
						if ( isset( $field['settings']['prefix_icon'] ) ) {
							$prefix_icon = $field['settings']['prefix_icon'];
						}
						if ( isset( $field['settings']['enqueue_style'] ) ) {
							wp_enqueue_style( $field['settings']['enqueue_style'] );
						}
					} else {
						wp_enqueue_style( apply_filters( 'theme_enqueue_style_font_awesome', 'thim-font-awesome' ) );
						$icons       = array(
							'none',
							'glass',
							'music',
							'search',
							'envelope-o',
							'heart',
							'star',
							'star-o',
							'user',
							'film',
							'th-large',
							'th',
							'th-list',
							'check',
							'remove',
							'close',
							'times',
							'search-plus',
							'search-minus',
							'power-off',
							'signal',
							'gear',
							'cog',
							'trash-o',
							'home',
							'file-o',
							'clock-o',
							'road',
							'download',
							'arrow-circle-o-down',
							'arrow-circle-o-up',
							'inbox',
							'play-circle-o',
							'rotate-right',
							'repeat',
							'refresh',
							'list-alt',
							'lock',
							'flag',
							'headphones',
							'volume-off',
							'volume-down',
							'volume-up',
							'qrcode',
							'barcode',
							'tag',
							'tags',
							'book',
							'bookmark',
							'print',
							'camera',
							'font',
							'bold',
							'italic',
							'text-height',
							'text-width',
							'align-left',
							'align-center',
							'align-right',
							'align-justify',
							'list',
							'dedent',
							'outdent',
							'indent',
							'video-camera',
							'photo',
							'image',
							'picture-o',
							'pencil',
							'map-marker',
							'adjust',
							'tint',
							'edit',
							'pencil-square-o',
							'share-square-o',
							'check-square-o',
							'arrows',
							'step-backward',
							'fast-backward',
							'backward',
							'play',
							'pause',
							'stop',
							'forward',
							'fast-forward',
							'step-forward',
							'eject',
							'chevron-left',
							'chevron-right',
							'plus-circle',
							'minus-circle',
							'times-circle',
							'check-circle',
							'question-circle',
							'info-circle',
							'crosshairs',
							'times-circle-o',
							'check-circle-o',
							'ban',
							'arrow-left',
							'arrow-right',
							'arrow-up',
							'arrow-down',
							'mail-forward',
							'share',
							'expand',
							'compress',
							'plus',
							'minus',
							'asterisk',
							'exclamation-circle',
							'gift',
							'leaf',
							'fire',
							'eye',
							'eye-slash',
							'warning',
							'exclamation-triangle',
							'plane',
							'calendar',
							'random',
							'comment',
							'magnet',
							'chevron-up',
							'chevron-down',
							'retweet',
							'shopping-cart',
							'folder',
							'folder-open',
							'arrows-v',
							'arrows-h',
							'bar-chart-o',
							'bar-chart',
							'twitter-square',
							'facebook-square',
							'camera-retro',
							'key',
							'gears',
							'cogs',
							'comments',
							'thumbs-o-up',
							'thumbs-o-down',
							'star-half',
							'heart-o',
							'sign-out',
							'linkedin-square',
							'thumb-tack',
							'external-link',
							'sign-in',
							'trophy',
							'github-square',
							'upload',
							'lemon-o',
							'phone',
							'square-o',
							'bookmark-o',
							'phone-square',
							'twitter',
							'facebook',
							'github',
							'unlock',
							'credit-card',
							'rss',
							'hdd-o',
							'bullhorn',
							'bell',
							'certificate',
							'hand-o-right',
							'hand-o-left',
							'hand-o-up',
							'hand-o-down',
							'arrow-circle-left',
							'arrow-circle-right',
							'arrow-circle-up',
							'arrow-circle-down',
							'globe',
							'wrench',
							'tasks',
							'filter',
							'briefcase',
							'arrows-alt',
							'group',
							'users',
							'chain',
							'link',
							'cloud',
							'flask',
							'cut',
							'scissors',
							'copy',
							'files-o',
							'paperclip',
							'save',
							'floppy-o',
							'square',
							'navicon',
							'reorder',
							'bars',
							'list-ul',
							'list-ol',
							'strikethrough',
							'underline',
							'table',
							'magic',
							'truck',
							'pinterest',
							'pinterest-square',
							'google-plus-square',
							'google-plus',
							'money',
							'caret-down',
							'caret-up',
							'caret-left',
							'caret-right',
							'columns',
							'unsorted',
							'sort',
							'sort-down',
							'sort-desc',
							'sort-up',
							'sort-asc',
							'envelope',
							'linkedin',
							'rotate-left',
							'undo',
							'legal',
							'gavel',
							'dashboard',
							'tachometer',
							'comment-o',
							'comments-o',
							'flash',
							'bolt',
							'sitemap',
							'umbrella',
							'paste',
							'clipboard',
							'lightbulb-o',
							'exchange',
							'cloud-download',
							'cloud-upload',
							'user-md',
							'stethoscope',
							'suitcase',
							'bell-o',
							'coffee',
							'cutlery',
							'file-text-o',
							'building-o',
							'hospital-o',
							'ambulance',
							'medkit',
							'fighter-jet',
							'beer',
							'h-square',
							'plus-square',
							'angle-double-left',
							'angle-double-right',
							'angle-double-up',
							'angle-double-down',
							'angle-left',
							'angle-right',
							'angle-up',
							'angle-down',
							'desktop',
							'laptop',
							'tablet',
							'mobile-phone',
							'mobile',
							'circle-o',
							'quote-left',
							'quote-right',
							'spinner',
							'circle',
							'mail-reply',
							'reply',
							'github-alt',
							'folder-o',
							'folder-open-o',
							'smile-o',
							'frown-o',
							'meh-o',
							'gamepad',
							'keyboard-o',
							'flag-o',
							'flag-checkered',
							'terminal',
							'code',
							'mail-reply-all',
							'reply-all',
							'star-half-empty',
							'star-half-full',
							'star-half-o',
							'location-arrow',
							'crop',
							'code-fork',
							'unlink',
							'chain-broken',
							'question',
							'info',
							'exclamation',
							'superscript',
							'subscript',
							'eraser',
							'puzzle-piece',
							'microphone',
							'microphone-slash',
							'shield',
							'calendar-o',
							'fire-extinguisher',
							'rocket',
							'maxcdn',
							'chevron-circle-left',
							'chevron-circle-right',
							'chevron-circle-up',
							'chevron-circle-down',
							'html5',
							'css3',
							'anchor',
							'unlock-alt',
							'bullseye',
							'ellipsis-h',
							'ellipsis-v',
							'rss-square',
							'play-circle',
							'ticket',
							'minus-square',
							'minus-square-o',
							'level-up',
							'level-down',
							'check-square',
							'pencil-square',
							'external-link-square',
							'share-square',
							'compass',
							'toggle-down',
							'caret-square-o-down',
							'toggle-up',
							'caret-square-o-up',
							'toggle-right',
							'caret-square-o-right',
							'euro',
							'eur',
							'gbp',
							'dollar',
							'usd',
							'rupee',
							'inr',
							'cny',
							'rmb',
							'yen',
							'jpy',
							'ruble',
							'rouble',
							'rub',
							'won',
							'krw',
							'bitcoin',
							'btc',
							'file',
							'file-text',
							'sort-alpha-asc',
							'sort-alpha-desc',
							'sort-amount-asc',
							'sort-amount-desc',
							'sort-numeric-asc',
							'sort-numeric-desc',
							'thumbs-up',
							'thumbs-down',
							'youtube-square',
							'youtube',
							'xing',
							'xing-square',
							'youtube-play',
							'dropbox',
							'stack-overflow',
							'instagram',
							'flickr',
							'adn',
							'bitbucket',
							'bitbucket-square',
							'tumblr',
							'tumblr-square',
							'long-arrow-down',
							'long-arrow-up',
							'long-arrow-left',
							'long-arrow-right',
							'apple',
							'windows',
							'android',
							'linux',
							'dribbble',
							'skype',
							'foursquare',
							'trello',
							'female',
							'male',
							'gittip',
							'sun-o',
							'moon-o',
							'archive',
							'bug',
							'vk',
							'weibo',
							'renren',
							'pagelines',
							'stack-exchange',
							'arrow-circle-o-right',
							'arrow-circle-o-left',
							'toggle-left',
							'caret-square-o-left',
							'dot-circle-o',
							'wheelchair',
							'vimeo-square',
							'turkish-lira',
							'try',
							'plus-square-o',
							'space-shuttle',
							'slack',
							'envelope-square',
							'wordpress',
							'openid',
							'institution',
							'bank',
							'university',
							'mortar-board',
							'graduation-cap',
							'yahoo',
							'google',
							'reddit',
							'reddit-square',
							'stumbleupon-circle',
							'stumbleupon',
							'delicious',
							'digg',
							'pied-piper',
							'pied-piper-alt',
							'drupal',
							'joomla',
							'language',
							'fax',
							'building',
							'child',
							'paw',
							'spoon',
							'cube',
							'cubes',
							'behance',
							'behance-square',
							'steam',
							'steam-square',
							'recycle',
							'automobile',
							'car',
							'cab',
							'taxi',
							'tree',
							'spotify',
							'deviantart',
							'soundcloud',
							'database',
							'file-pdf-o',
							'file-word-o',
							'file-excel-o',
							'file-powerpoint-o',
							'file-photo-o',
							'file-picture-o',
							'file-image-o',
							'file-zip-o',
							'file-archive-o',
							'file-sound-o',
							'file-audio-o',
							'file-movie-o',
							'file-video-o',
							'file-code-o',
							'vine',
							'codepen',
							'jsfiddle',
							'life-bouy',
							'life-buoy',
							'life-saver',
							'support',
							'life-ring',
							'circle-o-notch',
							'ra',
							'rebel',
							'ge',
							'empire',
							'git-square',
							'git',
							'hacker-news',
							'tencent-weibo',
							'qq',
							'wechat',
							'weixin',
							'send',
							'paper-plane',
							'send-o',
							'paper-plane-o',
							'history',
							'circle-thin',
							'header',
							'paragraph',
							'sliders',
							'share-alt',
							'share-alt-square',
							'bomb',
							'soccer-ball-o',
							'futbol-o',
							'tty',
							'binoculars',
							'plug',
							'slideshare',
							'twitch',
							'yelp',
							'newspaper-o',
							'wifi',
							'calculator',
							'paypal',
							'google-wallet',
							'cc-visa',
							'cc-mastercard',
							'cc-discover',
							'cc-amex',
							'cc-paypal',
							'cc-stripe',
							'bell-slash',
							'bell-slash-o',
							'trash',
							'copyright',
							'at',
							'eyedropper',
							'paint-brush',
							'birthday-cake',
							'area-chart',
							'pie-chart',
							'line-chart',
							'lastfm',
							'lastfm-square',
							'toggle-off',
							'toggle-on',
							'bicycle',
							'bus',
							'ioxhost',
							'angellist',
							'cc',
							'shekel',
							'sheqel',
							'ils',
							'meanpath'
						);
						$prefix_icon = 'fa fa-';
						$icons       = apply_filters( 'thim_core_widget_standard_icons', $icons );
					}

					$output = '<div class="wrapper_icon"><input type="hidden" name="' . $this->so_get_field_name( $name, $repeater ) . '" class="wpb_vc_param_value" value="' . esc_attr( $value ) . '" id="trace"/>
					<div class="icon-preview"><i class="' . $prefix_icon . esc_attr( $value ) . '"></i></div>';
					$output .= '<input class="search" type="text" placeholder="Search" />';
					$output .= '<div id="icon-dropdown">';
					$output .= '<ul class="icon-list">';
					foreach ( $icons as $icon ) {
						$selected = ( $icon == esc_attr( $value ) ) ? 'class="selected"' : '';
						$output   .= '<li ' . $selected . ' data-icon="' . $prefix_icon . $icon . '"><i class="' . $prefix_icon . $icon . '"></i></li>';
					}
					$output .= '</ul>';
					$output .= '</div></div>';
					$output .= '<script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery(".search").keyup(function(){
                            var filter = jQuery(this).val(), count = 0;
                            jQuery(".icon-list li").each(function(){
                                    // If the list item does not contain the text phrase fade it out
                                    if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                                            jQuery(this).fadeOut();
                                    } else {
                                            jQuery(this).show();
                                            count++;
                                    }
                            });
                        });
                    });
                    jQuery("#icon-dropdown li").click(function() {
                        jQuery(this).attr("class","selected").siblings().removeAttr("class");
                        var icon = jQuery(this).attr("data-icon");
                        jQuery(this).closest(".wrapper_icon").find(".wpb_vc_param_value").val(icon);
                        jQuery(this).closest(".wrapper_icon").find(".icon-preview").html("<i class=\'"+icon+"\'></i>");
				});
				</script>';
					echo ent2ncr( $output );
					?>
					<?php
					break;
				case 'sobp_datetime':
					?>
					<input type="text" name="<?php echo esc_attr( $this->so_get_field_name( $name, $repeater ) ) ?>"
						   id="<?php echo esc_attr( $field_id ) ?>" value="<?php echo esc_attr( $value ) ?>"
						   class="bp-datetimepicker widefat thim-widget-input"/>

					<?php
					break;

				default:
					if ( count( $custom_filed_type_sanitize ) > 0 ) {
						foreach ( $custom_filed_type_sanitize as $index => $field_type ) {
							if ( $field['type'] == $field_type['type'] ) {
								echo $field_type['form'];
								break;
							}
						}
						break;
					}

					esc_html_e( 'Unknown Field', 'fa-thim-core' );
					break;
			}

			if ( ! empty( $field['description'] ) ) { ?>
				<div class="bp-widget-field-description"><?php echo ent2ncr( $field['description'] ) ?></div><?php
			}
			?></div><?php
		}

		/**
		 * Get the template name that we'll be using to render this widget.
		 *
		 * @param $instance
		 *
		 * @return mixed
		 */
		function get_template_name() {
			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			return $config_class::$base;
		}


		/**
		 * Register frontend scripts.
		 */
		function register_frontend_scripts() {
			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			$config_class::register_scripts();
		}


		/**
		 * Register frontend scripts.
		 */
		function enqueue_frontend_scripts() {
			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			$config_class::enqueue_scripts();
		}

		function global_widget_css_inline() {
			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			return $config_class::_widget_css_inline();

		}

		/**
		 * By default, or overwritten in widgets.
		 */
		function get_config_options() {
			if ( ! class_exists( $this->config_class ) ) {
				return;
			}

			/**
			 * @var $config_class Thim_Builder_Abstract_Config
			 */
			$config_class = new $this->config_class();

			return ( apply_filters( 'thim_so_custom_option_' . $this->base, $config_class::$options ) );

		}
	}
}