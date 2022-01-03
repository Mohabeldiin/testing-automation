<?php

namespace MyHomeCore\Estates;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;
use MyHomeCore\Estates\Prices\Currencies;
use MyHomeCore\Terms\Term_Factory;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use function MyHomeCore\My_Home_Core;

/**
 * Class Estate_Settings
 *
 * @package MyHomeCore\Estates
 */
class Estate_Settings {

	/**
	 * Estate_Settings constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_fields' ), 100 );
		add_action( 'init', array( $this, 'register_post_type' ), 11 );
		add_action( 'add_meta_boxes', array( $this, 'set_meta_box' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'acf/update_value/name=estate_location', array( $this, 'update_estate_location' ), 10, 3 );
		add_filter( 'manage_estate_posts_columns', array( $this, 'columns_head' ) );
		add_action( 'manage_estate_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
		add_action( 'wp_insert_post', array( $this, 'set_views' ), 10, 3 );
		add_action( 'edit_form_top', array( $this, 'order_meta_boxes' ), 0, 11 );
		add_filter( 'post_type_link', array( $this, 'estate_permalink' ), 10, 2 );
		add_action( 'save_post_estate', [ $this, 'update_taxonomies' ] );
		add_action( 'add_meta_boxes', array( $this, 'multiple_agents' ) );

		add_filter( 'query_vars', function ( $vars ) {

			return $vars;
		} );

		add_filter( 'use_block_editor_for_post_type', static function ( $isEnabled, $postType ) {
			if ( $postType === 'estate' ) {
				return ! empty( My_Home_Core()->settings->get( 'editor' ) );
			}

			return $isEnabled;
		}, 10, 2 );

		add_filter( 'wpseo_sitemap_urlimages', function ( $images, $post_id ) {
			$gallery = get_post_meta( $post_id, 'estate_gallery', true );
			if ( empty( $gallery ) || ! is_array( $gallery ) ) {
				return $images;
			}
			foreach ( $gallery as $image_id ) {
				$images[] = [
					'src'   => wp_get_attachment_image_url( $image_id, 'full' ),
					'title' => get_the_title( $image_id )
				];
			}

			return $images;
		}, 10, 2 );

		//	    add_filter('use_block_editor_for_post_type', function($gutenberg_filter, $post_type) {
		//		    if ($post_type === 'estate') return false;
		//		    return $gutenberg_filter;
		//        }, 10, 2);

//        add_filter('use_block_editor_for_post_type', static function ($isEnabled, $postType) {
//            if ($postType === 'estate') {
//                return false;
//            }
//
//            return $isEnabled;
//        }, 10, 2);

		add_action( 'before_delete_post', function ( $postId, \WP_Post $post ) {
			if ( $post->post_type !== 'estate' && ! empty( My_Home_Theme()->settings->get( 'mh-estate_delete-images' ) ) ) {
				return;
			}

			$gallery = get_post_meta( $post->ID, 'estate_gallery', true );

			foreach ( $gallery as $imageId ) {
				wp_delete_attachment( $imageId );
			}
		}, 10, 2 );

		add_filter( 'rank_math/sitemap/urlimages', function ( $images, $post_id ) {
			$post = get_post( $post_id );
			if ( ! $post || $post->post_type !== 'estate' ) {
				return $images;
			}

			$gallery = get_post_meta( $post_id, 'estate_gallery', true );
			if ( ! empty( $gallery ) ) {
				foreach ( $gallery as $imageId ) {
					$image = get_post( $imageId );
					if ( ! empty( $image ) ) {
						$src = wp_get_attachment_image_src( $imageId, 'full' );

						$images[] = [
							'src'   => $src[0],
							'title' => $image->post_name,
							'alt'   => $image_alt = get_post_meta( $imageId, '_wp_attachment_image_alt', true ),
						];
					}
				}
			}

			return $images;
		}, 10, 2 );
	}

	/**
	 * @param string $postType
	 */
	public function multiple_agents( $postType ) {
		if ( $postType != 'estate' ) {
			return;
		}
		add_meta_box( 'meta-box-id', esc_html__( 'Display additional user profiles in the sidebar', 'myhome-core' ), array(
			$this,
			'multiple_agents_meta'
		), null, 'side', 'high' );
	}

	/**
	 * @param \WP_Post $post
	 */
	public function multiple_agents_meta( $post ) {
		if ( $post->post_type != 'estate' ) {
			return;
		}
		$agents         = get_users( array(
			'exclude' => $post->post_author
		) );
		$current_agents = get_post_meta( $post->ID, 'myhome_agents', true );
		if ( empty( $current_agents ) || ! is_array( $current_agents ) ) {
			$current_agents = array();
		}
		?>
        <select name="myhome_agents[]" class="selectize" multiple>
			<?php foreach ( $agents as $agent ) :
				/* @var $agent \WP_User */
				?>
                <option
                        value="<?php echo esc_attr( $agent->ID ); ?>"
					<?php if ( in_array( $agent->ID, $current_agents ) ) : ?>
                        selected
					<?php endif; ?>
                >
					<?php echo esc_html( $agent->display_name ); ?>
                </option>
			<?php endforeach; ?>
        </select>
		<?php
	}

	public function update_taxonomies( $post_id ) {
		if ( isset( $_POST['_inline_edit'] ) && wp_verify_nonce( $_POST['_inline_edit'], 'inlineeditnonce' ) ) {
			return;
		}

		if ( isset( $_POST['myhome_agents'] ) ) {
			update_post_meta( $post_id, 'myhome_agents', $_POST['myhome_agents'] );
		} else {
			update_post_meta( $post_id, 'myhome_agents', array() );
		}

		if ( ! isset( $_POST['tax_input'] ) ) {
			return;
		}

		foreach ( $_POST['tax_input'] as $taxonomy => $terms ) {
			$terms = array_filter( $terms, function ( $term ) {
				return ! empty( $term );
			} );
			wp_set_post_terms( $post_id, $terms, $taxonomy );
		}
	}

	/**
	 * @param \string $post_link
	 * @param \WP_Post $post
	 *
	 * @return \string
	 */
	public function estate_permalink( $post_link, $post ) {
		global $myhome_redux;

		if ( $post->post_type == 'estate' && ! empty( $myhome_redux['mh-breadcrumbs'] ) ) {
			$attributes = Breadcrumbs::get_attributes();

			foreach ( $attributes as $attribute ) {
				$terms = get_the_terms( $post->ID, $attribute->get_slug() );

				if ( empty( $terms ) ) {
					return site_url() . '/?post_type=estate&p=' . $post->ID;
				}

				if ( is_array( $terms ) ) {
					$post_link = str_replace( '%' . $attribute->get_slug() . '%', array_pop( $terms )->slug, $post_link );
				}
			}
		}

		if ( strpos( $post_link, '/%' ) !== false && strpos( $post_link, '%/' ) !== false ) {
			return site_url() . '/?post_type=estate&p=' . $post->ID;
		}

		return $post_link;
	}

	public function order_meta_boxes() {
		global $post;
		if ( $post->post_type != 'estate' ) {
			return;
		}
		global $wp_meta_boxes;
		if ( isset( $wp_meta_boxes['estate']['normal']['high']['mm_general'] ) ) {
			$wp_meta_boxes['estate']['normal']['low']['mm_general'] =
				$wp_meta_boxes['estate']['normal']['high']['mm_general'];
			unset( $wp_meta_boxes['estate']['normal']['high']['mm_general'] );
		}
		if ( isset( $wp_meta_boxes['estate']['normal']['high']['essb_metabox_optmize'] ) ) {
			$wp_meta_boxes['estate']['normal']['low']['essb_metabox_optmize'] =
				$wp_meta_boxes['estate']['normal']['high']['essb_metabox_optmize'];
			unset( $wp_meta_boxes['estate']['normal']['high']['essb_metabox_optmize'] );
		}
	}

	public function set_views( $post_id, $post, $update ) {
		if ( $post->post_type == 'estate' ) {
			add_post_meta( $post_id, 'estate_views', 0, true );
		}
	}

	public function columns_head( $columns ) {
		$columns_modified = array();
		foreach ( $columns as $key => $title ) {
			if ( $key == 'title' ) {
				$columns_modified ['featured_image'] = esc_html__( 'Image', 'myhome-core' );
				$columns_modified [ $key ]           = $title;
			} elseif ( $key == 'author' ) {
				$columns_modified [ $key ]  = esc_html__( 'User', 'myhome-core' );
				$columns_modified ['price'] = esc_html__( 'Price', 'myhome-core' );
			} else {
				$columns_modified [ $key ] = $title;
			}
		}

		return array_slice( $columns_modified, 0, 7 );
	}

	public function columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'featured_image' ) {
			$estate = Estate::get_instance( $post_ID );
			if ( $estate->has_image() ) {
				?>
                <div>
                <a href="<?php echo esc_url( get_edit_post_link( $post_ID ) ); ?>">
					<?php
					$image = wp_get_attachment_image_src( $estate->get_image_id(), 'medium' );
					if ( ! empty( $image ) ) :
						?>
                        <img src="<?php echo esc_attr( $image[0] ); ?>" alt="">
					<?php endif; ?>
                </a>
                </div><?php
			} else {
				$gallery = $estate->get_gallery();

				if ( ! empty( $gallery ) ) {
					?>
                    <div>
                    <a href="<?php echo esc_url( get_edit_post_link( $post_ID ) ); ?>">
						<?php
						$image = wp_get_attachment_image_src( $gallery[0]['ID'], 'medium' );
						if ( ! empty( $image ) ) :
							?>
                            <img src="<?php echo esc_attr( $image[0] ); ?>" alt="">
						<?php endif; ?>
                    </a>
                    </div><?php
				}
			}
		} elseif ( $column_name == 'price' ) {
			$estate = Estate::get_instance( $post_ID );
			foreach ( $estate->get_prices() as $key => $price ) {
				if ( $key ) {
					echo '<br>';
				}
				echo esc_html( $price->get_formatted() );
			}
		}
	}

	public function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$fields = array(
			array(
				'key'       => 'myhome_estate_tab_general',
				'label'     => esc_html__( 'General', 'myhome-core' ),
				'type'      => 'tab',
				'placement' => 'top',
				'wrapper'   => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
			),
			array(
				'key'           => 'myhome_estate_featured',
				'label'         => esc_html__( 'Featured', 'myhome-core' ),
				'name'          => 'estate_featured',
				'type'          => 'true_false',
				'default_value' => false,
				'wrapper'       => array(
					'class' => 'acf-1of3'
				),
			),
		);

		foreach ( Attribute_Factory::get_number() as $attribute ) {
			if ( $attribute instanceof Price_Attribute ) {
				$currencies = Currencies::get_all();

				if ( function_exists( 'icl_object_id' )
				     && ( \MyHomeCore\My_Home_Core()->current_language != \MyHomeCore\My_Home_Core()->default_language )
				     && ! is_admin()
				) {
					Term_Factory::$offer_types = array();
					do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->default_language );
					$offer_types = Term_Factory::get_offer_types();
					do_action( 'wpml_switch_language', \MyHomeCore\My_Home_Core()->current_language );
				} else {
					$offer_types = Term_Factory::get_offer_types();
				}

				foreach ( $currencies as $currency ) {
					$currency_key = $currency->get_key();
					if ( Price_Attribute::is_range() ) {
						$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
					} else {
						$price_keys = array( $currency_key );
					}
					$keys_count = count( $price_keys );

					foreach ( $price_keys as $key => $price_key ) {
						$label = $attribute->get_name() . ' (' . $currency->get_sign() . ')';

						if ( $keys_count == 2 && $key == 0 ) {
							$label .= ' ' . esc_html__( 'from', 'myhome-core' );
						} elseif ( $keys_count == 2 && $key == 1 ) {
							$label .= ' ' . esc_html__( 'to', 'myhome-core' );
						}

						$fields[] = array(
							'key'           => 'myhome_estate_attr_' . $price_key,
							'label'         => $label,
							'name'          => 'estate_attr_' . $price_key,
							'type'          => 'text',
							'default_value' => '',
							'wrapper'       => array(
								'class' => 'acf-1of3'
							),
						);
					}

					foreach ( $offer_types as $offer_type ) {
						if ( ! $offer_type->specify_price() ) {
							continue;
						}

						if ( $offer_type->is_price_range() ) {
							$price_keys = array( $currency_key . '_from', $currency_key . '_to' );
						} else {
							$price_keys = array( $currency_key );
						}

						$keys_count = count( $price_keys );

						foreach ( $price_keys as $key => $price_key ) {
							$label = $attribute->get_name() . ' (' . $currency->get_sign() . ')';

							if ( $keys_count == 2 && $key == 0 ) {
								$label .= ' ' . esc_html__( 'from', 'myhome-core' );
							} elseif ( $keys_count == 2 && $key == 1 ) {
								$label .= ' ' . esc_html__( 'to', 'myhome-core' );
							}

							$label .= ' | ' . $offer_type->get_name();

							$field_key = $price_key . '_offer_' . $offer_type->get_ID();


							$fields[] = array(
								'key'           => 'myhome_estate_attr_' . $field_key,
								'label'         => $label,
								'name'          => 'estate_attr_' . $field_key,
								'type'          => 'text',
								'default_value' => '',
								'wrapper'       => array(
									'class' => 'acf-1of3'
								),
							);
						}
					}
				}
				continue;
			}

			$fields[] = array(
				'key'           => 'myhome_estate_attr_' . $attribute->get_slug(),
				'label'         => $attribute->get_full_name(),
				'name'          => 'estate_attr_' . $attribute->get_slug(),
				'type'          => 'text',
				'default_value' => '',
				'wrapper'       => array(
					'class' => 'acf-1of3'
				),
			);
		}

		foreach ( Attribute_Factory::get_text_areas() as $attribute ) {
			$fields[] = array(
				'key'           => 'myhome_estate_attr_' . $attribute->get_slug(),
				'label'         => $attribute->get_full_name(),
				'name'          => 'estate_attr_' . $attribute->get_slug(),
				'type'          => 'wysiwyg',
				'default_value' => '',
				'wrapper'       => array(
					'class' => 'acf-1of1'
				)
			);
		}

		$fields[] = array(
			'key'          => 'myhome_estate_additional_features',
			'label'        => esc_html__( 'Additional features', 'myhome-core' ),
			'instructions' => esc_html__( 'You can use it to add some additional information to property e.g. Roof: Flat',
				'myhome-core' ),
			'name'         => 'estate_additional_features',
			'type'         => 'repeater',
			'wrapper'      => array(
				'class' => 'acf-1of1'
			),
			'sub_fields'   => array(
				array(
					'key'   => 'myhome_estate_additional_feature_name',
					'label' => esc_html__( 'Name', 'myhome-core' ),
					'name'  => 'estate_additional_feature_name',
					'type'  => 'text',
				),
				array(
					'key'   => 'myhome_estate_additional_feature_value',
					'label' => esc_html__( 'Value', 'myhome-core' ),
					'name'  => 'estate_additional_feature_value',
					'type'  => 'text',
				),
			)
		);

		$fields = array_merge(
			$fields, array(
				array(
					'key'   => 'myhome_estate_tab_location',
					'label' => esc_html__( 'Location', 'myhome-core' ),
					'type'  => 'tab',
				),
				array(
					'key'        => 'myhome_estate_location',
					'label'      => esc_html__( 'Location', 'myhome-core' ),
					'name'       => 'estate_location',
					'type'       => 'google_map',
					'center_lat' => floatval( \MyHomeCore\My_Home_Core()->settings->get( 'map-center_lat' ) ),
					'center_lng' => floatval( \MyHomeCore\My_Home_Core()->settings->get( 'map-center_lng' ) )
				),
				array(
					'key'          => 'myhome_estate_tab_gallery',
					'label'        => 'Gallery',
					'instructions' => esc_html__( 'Click "Add to gallery" below to upload files', 'myhome-core' ),
					'type'         => 'tab',
				),
				array(
					'key'          => 'myhome_estate_gallery',
					'label'        => 'Gallery',
					'name'         => 'estate_gallery',
					'type'         => 'gallery',
					'preview_size' => 'thumbnail',
					'library'      => 'all',
				)
			)
		);

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_plans'] ) ) {
			$fields = array_merge(
				$fields, array(
					array(
						'key'       => 'myhome_estate_tab_plans',
						'label'     => esc_html__( 'Plans', 'myhome-core' ),
						'type'      => 'tab',
						'placement' => 'left',
					),
					array(
						'key'          => 'myhome_estate_plans',
						'label'        => esc_html__( 'Plans', 'myhome-core' ),
						'name'         => 'estate_plans',
						'type'         => 'repeater',
						'button_label' => esc_html__( 'Add plan', 'myhome-core' ),
						'sub_fields'   => array(
							array(
								'key'   => 'myhome_estate_plans_name',
								'label' => esc_html__( 'Name', 'myhome-core' ),
								'name'  => 'estate_plans_name',
								'type'  => 'text',
							),
							array(
								'key'   => 'myhome_estate_plans_image',
								'label' => esc_html__( 'Image', 'myhome-core' ),
								'name'  => 'estate_plans_image',
								'type'  => 'image',
							),
						),
					)
				)
			);
		}

		if ( ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_video'] ) ) {
			$fields = array_merge(
				$fields, array(
					array(
						'key'       => 'myhome_estate_tab_video',
						'label'     => esc_html__( 'Video', 'myhome-core' ),
						'type'      => 'tab',
						'placement' => 'left',
					),
					array(
						'key'   => 'myhome_estate_video',
						'label' => esc_html__( 'Video link (Youtube / Vimeo / Facebook / Twitter / Instagram / link to .mp4)',
							'myhome-core' ),
						'name'  => 'estate_video',
						'type'  => 'oembed',
					)
				)
			);
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_virtual_tour'] )
		     && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_virtual_tour'] )
		) {
			$fields = array_merge(
				$fields, array(
					array(
						'key'       => 'myhome_estate_tab_virtual_tour',
						'label'     => esc_html__( 'Virtual tour', 'myhome-core' ),
						'type'      => 'tab',
						'placement' => 'left',
					),
					array(
						'key'   => 'myhome_estate_virtual_tour',
						'label' => esc_html__( 'Add embed code', 'myhome-core' ),
						'name'  => 'virtual_tour',
						'type'  => 'text',
					)
				)
			);
		}

		if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_attachments'] )
		     && ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_attachments'] )
		) {
			$fields = array_merge(
				$fields, array(
					array(
						'key'       => 'myhome_estate_tab_attachments',
						'label'     => esc_html__( 'Attachments', 'myhome-core' ),
						'type'      => 'tab',
						'placement' => 'left',
					),
					array(
						'key'          => 'myhome_estate_attachments',
						'label'        => esc_html__( 'Add attachment', 'myhome-core' ),
						'name'         => 'estate_attachments',
						'type'         => 'repeater',
						'button_label' => esc_html__( 'Add attachment', 'myhome-core' ),
						'sub_fields'   => array(
							array(
								'key'   => 'myhome_estate_attachment_name',
								'label' => esc_html__( 'Name', 'myhome-core' ),
								'name'  => 'estate_attachment_name',
								'type'  => 'text',
							),
							array(
								'key'   => 'myhome_estate_attachment_file',
								'label' => esc_html__( 'File', 'myhome-core' ),
								'name'  => 'estate_attachment_file',
								'type'  => 'file',
							),
						)
					)
				)
			);
		}

		$fields = array_merge( $fields, array(
			array(
				'key'       => 'myhome_estate_sidebar',
				'label'     => esc_html__( 'Sidebar elements', 'myhome-core' ),
				'type'      => 'tab',
				'placement' => 'left'
			),
			array(
				'key'          => 'myhome_estate_sidebar_elements',
				'label'        => esc_html__( 'Add element', 'myhome-core' ),
				'name'         => 'estate_sidebar_elements',
				'type'         => 'repeater',
				'button_label' => esc_html__( 'Add element', 'myhome-core' ),
				'sub_fields'   => array(
					array(
						'key'     => 'myhome_estate_sidebar_element_image',
						'label'   => esc_html__( 'Image', 'myhome-core' ),
						'name'    => 'estate_sidebar_element_image',
						'type'    => 'image',
						'wrapper' => array(
							'class' => 'mh-sidebar-element__image'
						)
					),
					array(
						'key'   => 'myhome_estate_sidebar_element_text',
						'label' => esc_html__( 'Text', 'myhome-core' ),
						'name'  => 'estate_sidebar_element_text',
						'type'  => 'text'
					),
					array(
						'key'   => 'myhome_estate_sidebar_element_link',
						'label' => esc_html__( 'Link (optional)', 'myhome-core' ),
						'name'  => 'estate_sidebar_element_link',
						'type'  => 'text'
					)
				)
			)
		) );

		acf_add_local_field_group(
			array(
				'key'        => 'myhome_estate',
				'title'      => '<span class="dashicons dashicons-admin-home"></span> ' . esc_html__( 'Property details',
						'myhome-core' ),
				'fields'     => $fields,
				'menu_order' => 10,
				'location'   => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'estate',
						),
					),
				),
			)
		);
	}

	public function register_sidebars() {
		return;
		foreach ( Attribute_Factory::get_widgets() as $attribute ) {
			register_sidebar(
				array(
					'name'          => $attribute->get_name(),
					'id'            => $attribute->get_slug(),
					'before_widget' => '<div class="' . esc_attr( $attribute->get_class() ) . '">',
					'after_widget'  => '</div>'
				)
			);
		}
	}

	public function register_taxonomies() {
		foreach ( Attribute_Factory::get_text() as $attribute ) {
			$labels = array(
				'name'              => $attribute->get_name(),
				'singular_name'     => $attribute->get_name(),
				'search_items'      => sprintf( esc_html__( 'Search %s', 'myhome-core' ), $attribute->get_name() ),
				'all_items'         => sprintf( esc_html__( 'All %s', 'myhome-core' ), $attribute->get_name() ),
				'parent_item'       => sprintf( esc_html__( 'Parent %s', 'myhome-core' ), $attribute->get_name() ),
				'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'myhome-core' ), $attribute->get_name() ),
				'edit_item'         => sprintf( esc_html__( 'Edit %s', 'myhome-core' ), $attribute->get_name() ),
				'update_item'       => sprintf( esc_html__( 'Update %s', 'myhome-core' ), $attribute->get_name() ),
				'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'myhome-core' ), $attribute->get_name() ),
				'new_item_name'     => sprintf( esc_html__( 'New %s Name', 'myhome-core' ), $attribute->get_name() ),
				'menu_name'         => $attribute->get_name()
			);

			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'hierarchical'       => false,
				'show_admin_column'  => true,
				'query_vars'         => true,
				'publicly_queryable' => true,
				'has_archive'        => true,
				'rewrite'            => array( 'slug' => $attribute->get_slug() ),
				'capabilities'       => array(
					'manage_terms' => 'manage_categories',
					'edit_terms'   => 'manage_categories',
					'delete_terms' => 'manage_categories',
					'assign_terms' => 'edit_estates'
				)
			);

			register_taxonomy( $attribute->get_slug(), 'estate', $args );
		}

		do_action( 'myhome_attributes_registered' );
	}

	public function register_post_type() {
		// define post type slug
		$slug = \MyHomeCore\My_Home_Core()->settings->get( 'estate-slug' );
		if ( empty( $slug ) ) {
			$slug = 'properties';
		}
		$archive_slug = $slug;

		$breadcrumb_attributes = Breadcrumbs::get_attributes();
		foreach ( $breadcrumb_attributes as $attribute ) {
			$slug .= '/%' . $attribute->get_slug() . '%';
		}

		$supports = array(
			'title',
			'author',
			'editor',
			'thumbnail',
			'excerpt',
		);

		$enable_comments = \MyHomeCore\My_Home_Core()->settings->get( 'property-enabled_comments' );
		if ( ! empty( $enable_comments ) ) {
			$supports[] = 'comments';
		}

		register_post_type(
			'estate', array(
				'labels'             => array(
					'name'               => esc_html__( 'Properties', 'myhome-core' ),
					'singular_name'      => esc_html__( 'Property', 'myhome-core' ),
					'menu_name'          => esc_html__( 'Properties', 'myhome-core' ),
					'name_admin_bar'     => esc_html__( 'Property', 'myhome-core' ),
					'add_new'            => esc_html__( 'Add New Property', 'myhome-core' ),
					'add_new_item'       => esc_html__( 'Add New Property', 'myhome-core' ),
					'new_item'           => esc_html__( 'New Property', 'myhome-core' ),
					'edit_item'          => esc_html__( 'Edit Property', 'myhome-core' ),
					'view_item'          => esc_html__( 'View Property', 'myhome-core' ),
					'all_items'          => esc_html__( 'Properties', 'myhome-core' ),
					'search_items'       => esc_html__( 'Search property', 'myhome-core' ),
					'not_found'          => esc_html__( 'No Property Found found.', 'myhome-core' ),
					'not_found_in_trash' => esc_html__( 'No Property found in Trash.', 'myhome-core' )
				),
				'show_in_rest'       => true,
				'query_var'          => true,
				'public'             => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'has_archive'        => $archive_slug,
				'menu_position'      => 4,
				'menu_icon'          => 'dashicons-admin-home',
				'map_meta_cap'       => true,
				'rewrite'            => array( 'slug' => $slug ),
				'supports'           => $supports,
				'publicly_queryable' => true,
			)
		);
	}

	public function set_meta_box() {
		foreach ( Attribute_Factory::get_text() as $attribute ) {
			remove_meta_box( 'tagsdiv-' . $attribute->get_slug(), 'estate', 'side' );
		}

		add_meta_box(
			'myhome_attributes_box',
			esc_html__( 'Property Attributes', 'myhome-core' ),
			array( $this, 'meta_box' ),
			'estate',
			'normal',
			'high'
		);
	}

	public function meta_box( $object ) {
		$attributes = Attribute_Factory::get_text();
		$estate_id  = $object;
		include MYHOME_CORE_VIEWS . 'attributes-admin-meta-box.php';
	}

	public static function create_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		// myhome_locations store locations. It helps when filtering properties by locations (lat/lng)
		$table_name = $wpdb->prefix . 'myhome_locations';

		$query = "CREATE TABLE $table_name (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id bigint(20) UNSIGNED NOT NULL,
			lat decimal(10, 8) NOT NULL,
			lng decimal(11, 8) NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );
	}

	public function update_estate_location( $value, $post_id, $field ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'myhome_locations';

		$wpdb->delete(
			$table_name,
			array(
				'post_id' => $post_id
			),
			array(
				'%d'
			)
		);

		if ( empty( $value ) || ! isset( $value['lat'] ) || ! isset( $value['lng'] ) || empty( $value['lat'] )
		     || empty( $value['lng'] )
		) {
			return $value;
		}

		$wpdb->insert(
			$table_name,
			array(
				'post_id' => $post_id,
				'lat'     => $value['lat'],
				'lng'     => $value['lng']
			),
			array(
				'%s',
				'%f',
				'%f'
			)
		);

		return $value;
	}

}