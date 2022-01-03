<?php

namespace MyHomeCore\Attributes;


/**
 * Class Attributes_Settings
 * @package MyHomeCore\Attributes
 */
class Attributes_Settings {

	/**
	 * Attributes_Settings constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ), 99 );

		if ( is_admin() ) {
			$this->register_fields();
			$this->register_options();
		}
	}

	/**
	 * Create option pages for attributes
	 */
	public function register_options() {
		foreach ( Attribute_Factory::get() as $attribute ) {
			$attribute->register_options_page();
		}
	}

	/*
	 * Register custom fields for taxonomies.
	 */
	public function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$text_attributes = Attribute_Factory::get_text();

		foreach ( $text_attributes as $attribute ) {
			$fields = array(
				array(
					'key'   => 'myhome_term_image',
					'label' => esc_html__( 'Image', 'myhome-core' ),
					'name'  => 'term_image',
					'type'  => 'image'
				),
				array(
					'key'          => 'myhome_term_image_wide',
					'label'        => esc_html__( 'Image wide', 'myhome-core' ),
					'instructions' => esc_html__( 'Recommended size 1920x500 px', 'myhome-core' ),
					'name'         => 'term_image_wide',
					'type'         => 'image'
				),
			);

			if ( $attribute->has_parent() && $attribute->get_parent_type() == Text_Attribute::PARENT_TYPE_MANUAL ) {
				$parent_attribute = $attribute->get_parent();

				if ( $parent_attribute != false ) {
					$fields[] = array(
						'key'           => 'myhome_term_parent_' . $attribute->get_ID(),
						'label'         => sprintf( esc_html__( 'Parent %s', 'myhome-core' ), $parent_attribute->get_name() ),
						'name'          => 'term_parent_' . $attribute->get_ID(),
						'type'          => 'taxonomy',
						'field_type'    => 'select',
						'taxonomy'      => $parent_attribute->get_slug(),
						'return_format' => 'object'
					);
				}
			}

			acf_add_local_field_group(
				array(
					'key'      => 'myhome_area_' . $attribute->get_ID(),
					'title'    => esc_html__( 'Settings', 'myhome-core' ),
					'location' => array(
						array(
							array(
								'param'    => 'taxonomy',
								'operator' => '==',
								'value'    => $attribute->get_slug()
							)
						),
					),
					'fields'   => $fields
				)
			);
		}

		$offer_type = Attribute_Factory::get_offer_type();

		if ( $offer_type != false ) {
			acf_add_local_field_group(
				array(
					'key'      => 'myhome_property_type',
					'location' => array(
						array(
							array(
								'param'    => 'taxonomy',
								'operator' => '==',
								'value'    => $offer_type->get_slug(),
							)
						)
					),
					'fields'   => array(
						array(
							'key'   => 'myhome_offer_type_after_price',
							'label' => esc_html__( 'Display after price', 'myhome-core' ),
							'name'  => 'offer_type_after_price',
							'type'  => 'text'
						),
						array(
							'key'   => 'myhome_offer_type_before_price',
							'label' => esc_html__( 'Display before price', 'myhome-core' ),
							'name'  => 'offer_type_before_price',
							'type'  => 'text'
						),
						array(
							'key'           => 'myhome_offer_type_label',
							'label'         => esc_html__( 'Show label on property cards', 'myhome-core' ),
							'name'          => 'offer_type_label',
							'type'          => 'true_false',
							'default_value' => true
						),
						array(
							'key'               => 'myhome_offer_type_label_color',
							'label'             => esc_html__( 'Label text color', 'myhome-core' ),
							'name'              => 'offer_type_label_color',
							'type'              => 'color_picker',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'myhome_offer_type_label',
										'operator' => '==',
										'value'    => '1'
									)
								)
							)
						),
						array(
							'key'               => 'myhome_offer_type_label_bg',
							'label'             => esc_html__( 'Label background color', 'myhome-core' ),
							'name'              => 'offer_type_label_bg',
							'type'              => 'color_picker',
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'myhome_offer_type_label',
										'operator' => '==',
										'value'    => '1'
									)
								)
							)
						),
						array(
							'key'           => 'myhome_offer_type_specify_price',
							'label'         => esc_html__( 'Add additional price field', 'myhome-core' ),
							'instructions'  => 'This option can be useful to create multiple rent labels and have unique prices for /day /week /month rent',
							'name'          => 'offer_type_specify_price',
							'type'          => 'true_false',
							'default_value' => false
						),
						array(
							'key'               => 'myhome_offer_type_is_price_range',
							'label'             => esc_html__( 'Add additional price field - price range "from" - "to"', 'myhome-core' ),
							'name'              => 'offer_type_is_price_range',
							'type'              => 'true_false',
							'default_value'     => false,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'myhome_offer_type_specify_price',
										'operator' => '==',
										'value'    => '1'
									)
								)
							)
						),
						array(
							'key'           => 'myhome_offer_type_exclude',
							'label'         => esc_html__( 'Exclude from search results', 'myhome-core' ),
							'instructions'  => 'This option can be useful to create "sold" label that will keep properties in the database, link to property will still work for your users (no 404) and it will be better for your SEO',
							'name'          => 'offer_type_exclude',
							'type'          => 'true_false',
							'default_value' => 0
						)
					)
				)
			);
		}

		/*
		 * Property type dependency.
		 */
		$property_type = Attribute_Factory::get_property_type();
		$fields        = array();

		if ( $property_type === false ) {
			return;
		}

		foreach ( Attribute_Factory::get() as $attribute ) {
			if ( ! ( $attribute instanceof Text_Attribute || $attribute instanceof Number_Attribute )
			     || $attribute instanceof Property_Type_Attribute
			) {
				continue;
			}

			$fields[] = array(
				'key'           => 'myhome_property_type_' . $attribute->get_slug(),
				'label'         => $attribute->get_name(),
				'name'          => 'property_type_' . $attribute->get_slug(),
				'type'          => 'true_false',
				'default_value' => 1
			);
		}

		acf_add_local_field_group(
			array(
				'key'      => 'myhome_property_type_filters',
				'title'    => esc_html__( 'Filters', 'myhome-core' ),
				'location' => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => $property_type->get_slug(),
						)
					)
				),
				'fields'   => $fields
			)
		);
	}

	/*
     * Menu with attribute settings
     */
	public function add_menu() {
		add_menu_page(
			esc_html__( 'MyHome Panel', 'myhome-core' ),
			esc_html__( 'MyHome Panel', 'myhome-core' ),
			'administrator',
			'myhome_attributes',
			array( $this, 'admin_page' ),
			'',
			3
		);
	}

	/*
	 * Manage attributes
	 */
	public function admin_page() {
		require MYHOME_CORE_VIEWS . 'attributes-admin-page.php';
	}

	public static function create_table( $just_import = false ) {
		/*
		 * Create attributes table and import predefined attributes.
		 * IMPORTANT Attributes with base set to 1 should never be deleted. It will cause many errors.
		 */
		$base_attributes = array(
			(object) array(
				'attribute_name' => esc_html__( 'Property type', 'myhome-core' ),
				'attribute_slug' => 'property-type',
				'attribute_type' => 'taxonomy',
				'base'           => 1,
				'base_slug'      => 'property_type'
			),
			(object) array(
				'attribute_name' => esc_html__( 'Offer type', 'myhome-core' ),
				'attribute_slug' => 'offer-type',
				'attribute_type' => 'taxonomy',
				'base'           => 1,
				'base_slug'      => 'offer_type'
			),
			(object) array(
				'attribute_name' => esc_html__( 'City', 'myhome-core' ),
				'attribute_slug' => 'city',
				'attribute_type' => 'taxonomy',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Price', 'myhome-core' ),
				'attribute_slug' => 'price',
				'attribute_type' => 'field',
				'base'           => 1,
				'base_slug'      => 'price'
			),
			(object) array(
				'attribute_name' => esc_html__( 'Keyword', 'myhome-core' ),
				'attribute_slug' => 'keyword',
				'attribute_type' => 'core',
				'base'           => 2,
				'base_slug'      => 'keyword'
			),
			(object) array(
				'attribute_name' => esc_html__( 'Bedrooms', 'myhome-core' ),
				'attribute_slug' => 'price',
				'attribute_type' => 'field',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Bathrooms', 'myhome-core' ),
				'attribute_slug' => 'bathrooms',
				'attribute_type' => 'field',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Property Size', 'myhome-core' ),
				'attribute_slug' => 'property-size',
				'attribute_type' => 'field',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Lot Size', 'myhome-core' ),
				'attribute_slug' => 'lot-size',
				'attribute_type' => 'field',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Year', 'myhome-core' ),
				'attribute_slug' => 'year',
				'attribute_type' => 'field',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Features', 'myhome-core' ),
				'attribute_slug' => 'features',
				'attribute_type' => 'taxonomy',
				'base'           => 0,
				'base_slug'      => ''
			),
			(object) array(
				'attribute_name' => esc_html__( 'Property ID', 'myhome-core' ),
				'attribute_slug' => 'property-id',
				'attribute_type' => 'core',
				'base'           => 2,
				'base_slug'      => 'property_id'
			)
		);

		// Create attributes table
		global $wpdb;
		$table_name = $wpdb->prefix . 'myhome_attributes';

		if ( ! $just_import ) {
			$check_table = $wpdb->get_var( "SHOW TABLES LIKE '$table_name' " );
			if ( ! empty( $check_table ) && $check_table == $table_name ) {
				return;
			}
			$charset_collate = $wpdb->get_charset_collate();
			$query           = "CREATE TABLE IF NOT EXISTS $table_name (
			ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			attribute_name varchar(191) DEFAULT '' NOT NULL,
			attribute_slug varchar(191) DEFAULT '' NOT NULL,
			attribute_type varchar(20) DEFAULT '' NOT NULL,
			form_order int(11) UNSIGNED DEFAULT 0 NOT NULL,
			base int(11) UNSIGNED DEFAULT 0 NOT NULL,
            base_slug varchar(191) DEFAULT '' NOT NULL,
			PRIMARY KEY  (ID)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $query );
		}

		// import predefined attributes
		foreach ( $base_attributes as $attr ) {
			$check = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $table_name WHERE attribute_name = %s OR attribute_slug = %s",
					$attr->attribute_name,
					$attr->attribute_slug
				)
			);

			if ( $check ) {
				continue;
			}
			$form_order = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

			$wpdb->insert(
				$table_name, array(
					'attribute_name' => $attr->attribute_name,
					'attribute_slug' => $attr->attribute_slug,
					'attribute_type' => $attr->attribute_type,
					'form_order'     => $form_order,
					'base'           => $attr->base,
					'base_slug'      => $attr->base_slug
				)
			);
		}
	}

}