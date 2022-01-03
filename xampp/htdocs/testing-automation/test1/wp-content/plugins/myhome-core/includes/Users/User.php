<?php

namespace MyHomeCore\Users;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Common\Image;
use MyHomeCore\Common\Social_Icon;
use MyHomeCore\Components\Listing\Listing;
use MyHomeCore\Estates\Estate_Factory;
use WP_Query;

class User {

	/**
	 * @var \WP_User
	 */
	protected $user;

	/**
	 * @var array
	 */
	protected $meta;

	/**
	 * @var array
	 */
	protected $social_icons = array(
		'facebook'  => 'fa-facebook',
		'twitter'   => 'fa-twitter',
		'instagram' => 'fa-instagram',
		'linkedin'  => 'fa-linkedin',
	);

	/**
	 * User constructor.
	 *
	 * @param \WP_User $user
	 */
	public function __construct( \WP_User $user ) {
		$this->user = $user;
		$this->meta = get_user_meta( $user->ID );
	}

	/**
	 * @param \WP_User|int $user
	 *
	 * @return User
	 */
	public static function get_instance( $user ) {
		if ( is_int( $user ) ) {
			$user = get_user_by( 'id', $user );
		} elseif ( is_string( $user ) ) {
			$user = get_user_by( 'email', $user );
		}

		if ( $user instanceof \WP_User ) {
			return new User( $user );
		}
	}

	/**
	 * @return int
	 */
	public function get_ID() {
		return $this->user->ID;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		$user_name = apply_filters( 'myhome_user_name', $this->user->display_name, $this->user );

		return apply_filters(
			'wpml_translate_single_string',
			$user_name,
			'myhome-core',
			'User name ' . $user_name
		);
	}

	/**
	 * @return string
	 */
	public function get_login() {
		return $this->user->user_login;
	}

	/**
	 * @return bool
	 */
	public function has_phone() {
		$phone = $this->get_phone();

		return ! empty( $phone );
	}

	/**
	 * @return string
	 */
	public function get_phone() {
		if ( ! isset( $this->meta['agent_phone'] ) || empty( $this->meta['agent_phone'] ) ) {
			return '';
		}

		return apply_filters(
			'wpml_translate_single_string',
			$this->meta['agent_phone'][0],
			'myhome-core',
			'User phone' . $this->user->display_name
		);
	}

	/**
	 * @return string
	 */
	public function get_phone_href() {
		return str_replace( array( ' ', '-', '(', ')' ), '', $this->get_phone() );
	}

	/**
	 * @return string
	 */
	public function has_email() {
		return ! empty( $this->user->user_email );
	}

	/**
	 * @return string
	 */
	public function get_email() {
		return apply_filters(
			'wpml_translate_single_string',
			$this->user->user_email,
			'myhome-core',
			'User email ' . $this->user->display_name
		);
	}

	/**
	 * @return bool
	 */
	public function has_social_icons() {
		foreach ( $this->social_icons as $icon_key => $icon ) {
			if ( isset( $this->meta[ 'agent_' . $icon_key ] ) && ! empty( $this->meta[ 'agent_' . $icon_key ] ) ) {
				$i = $this->meta[ 'agent_' . $icon_key ][0];
			} else {
				$i = '';
			}

			if ( ! empty( $i ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return Social_Icon[]
	 */
	public function get_social_icons() {
		$icons = array();
		foreach ( $this->social_icons as $icon_key => $icon ) {
			if ( isset( $this->meta[ 'agent_' . $icon_key ] ) && ! empty( $this->meta[ 'agent_' . $icon_key ] ) ) {
				$i = $this->meta[ 'agent_' . $icon_key ][0];
			} else {
				$i = '';
			}

			if ( ! empty( $i ) ) {
				$icons[] = new Social_Icon( $icon, $i );
			}
		}

		return $icons;
	}

	/**
	 * @return bool
	 */
	public function has_image() {
		if ( ! isset( $this->meta['agent_image'] ) || empty( $this->meta['agent_image'] ) ) {
			return false;
		}

		return ! empty( $this->meta['agent_image'][0] );
	}

	/**
	 * @return bool|array
	 */
	public function get_image() {
		if ( ! isset( $this->meta['agent_image'] ) || empty( $this->meta['agent_image'] ) ) {
			return false;
		}

		return $this->meta['agent_image'][0];
	}

	/**
	 * @return string
	 */
	public function get_image_url() {
		if ( ! isset( $this->meta['agent_image'] ) || empty( $this->meta['agent_image'] ) ) {
			return '';
		}

		return empty( $this->meta['agent_image'][0] ) ? '' : wp_get_attachment_image_url( $this->meta['agent_image'][0] );
	}

	/**
	 * @return int
	 */
	public function get_image_id() {
		$image = $this->get_image();

		return empty( $image ) ? 0 : intval( $image );
	}

	/**
	 * @param string $thumbnail
	 * @param string $class
	 */
	public function image( $thumbnail = 'square', $class = '' ) {
		$image_id = $this->get_image_id();

		if ( ! empty( $image_id ) ) {
			Image::the_image( $image_id, $thumbnail, $this->get_name(), $class );
		}
	}

	/**
	 * @return string
	 */
	public function get_link() {
		return apply_filters( 'myhome_agent_url', get_author_posts_url( $this->user->ID ), $this );
	}

	/**
	 * @return bool
	 */
	public function has_description() {
		$description = $this->get_description();

		return ! empty( $description );
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return apply_filters(
			'wpml_translate_single_string',
			get_the_author_meta( 'description', $this->user->ID ),
			'Agents',
			'agent-description-' . $this->get_ID()
		);
	}

	/**
	 * @return string
	 */
	public function get_short_description() {
		return wp_trim_words(
			$this->get_description(),
			35,
			'...'
		);
	}

	/**
	 * @param string $status
	 *
	 * @return \MyHomeCore\Estates\Estates
	 */
	public function get_estates( $status = 'publish' ) {
		return Estate_Factory::get_from_user( $this->get_ID(), array( $status ) );
	}

	/**
	 * @return array
	 */
	public function get_data() {
		$social_icons = array();

		foreach ( $this->social_icons as $icon_key => $icon_class ) {
			$social_icons[ $icon_key ] = array(
				'key'   => $icon_key,
				'class' => $icon_class,
				'value' => get_user_meta( $this->user->ID, 'agent_' . $icon_key, true )
			);
		}

		return array(
			'id'           => $this->get_ID(),
			'login'        => $this->get_login(),
			'name'         => $this->get_name(),
			'display_name' => $this->get_name(),
			'link'         => $this->get_link(),
			'email'        => $this->get_email(),
			'phone'        => $this->get_phone(),
			'fields'       => $this->get_fields()->get_data(),
			'roles'        => $this->user->roles,
			'image'        => array(
				'url' => $this->get_image_url(),
				'id'  => $this->get_image_id()
			),
			'image_url'    => $this->get_image_url()
		);
	}

	public function get_social_data() {
		$social_icons = array();

		foreach ( $this->social_icons as $icon_key => $icon_class ) {
			$social_icons[ $icon_key ] = array(
				'key'   => $icon_key,
				'class' => $icon_class,
				'value' => get_user_meta( $this->user->ID, 'agent_' . $icon_key, true )
			);
		}

		return $social_icons;
	}

	/**
	 * @return Fields\Fields
	 */
	public function get_fields() {
		return new Fields\Fields( $this->get_ID() );
	}

	/**
	 * @return string
	 */
	public function get_hash() {
		return $this->user->data->user_pass;
	}

	/**
	 * @param int|null $user_id
	 *
	 * @return User
	 */
	public static function get_user_by_id( $user_id = null ) {
		if ( is_null( $user_id ) ) {
			$author_name = get_query_var( 'author_name' );
			$user        = $author_name ? get_user_by( 'slug', $author_name ) : get_userdata( get_query_var( 'author' ) );
		} else {
			$user = get_user_by( 'ID', $user_id );
		}

		return self::get_instance( $user );
	}

	public function listing() {
		$show_advanced                  = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_advanced'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_advanced'] );
		$show_clear                     = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_clear'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_clear'] );
		$show_sort_by                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by'] );
		$show_view_types                = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_view_types'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_view_types'] );
		$show_gallery                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_gallery'] ) ? true : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_gallery'] );
		$advanced_number                = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_advanced_number'] ) ? 3 : intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_advanced_number'] );
		$show_results_number            = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing_show-results-number'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing_show-results-number'] );
		$listing_type                   = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-type'] ) ? 'load_more' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-type'];
		$show_sort_by_newest            = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_newest'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_newest'] );
		$show_sort_by_popular           = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_popular'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_popular'] );
		$show_sort_by_price_high_to_low = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_high_to_low'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_high_to_low'] );
		$show_sort_by_price_low_to_high = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_low_to_high'] ) ? true : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_price_low_to_high'] );
		$show_sort_by_alphabetically    = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_alphabetically'] ) ? false : ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_alphabetically'] );
		$search_form                    = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form'] ) ? 'default' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form'];
		$sort_by                        = ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_default'] ) ? 'newest' : \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_sort_by_default'];

		$atts = array(
			'lazy_loading'                   => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-lazy_loading'] ? 'true' : 'false',
			'lazy_loading_limit'             => intval( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_number'] ),
			'load_more_button'               => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-load_more_button_label'],
			'listing_default_view'           => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-default_view'],
			'estates_per_page'               => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-estates_limit'],
			'search_form_position'           => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-search_form_position'],
			'label'                          => \MyHomeCore\My_Home_Core()->settings->props['mh-listing-label'],
			'listing_sort_by'                => $sort_by,
			'search_form_advanced_number'    => $advanced_number,
			'show_advanced'                  => $show_advanced ? 'true' : 'false',
			'show_clear'                     => $show_clear ? 'true' : 'false',
			'show_sort_by'                   => $show_sort_by ? 'true' : 'false',
			'show_view_types'                => $show_view_types ? 'true' : 'false',
			'agent_id'                       => $this->user->ID,
			'show_gallery'                   => $show_gallery ? 'true' : 'false',
			'show_results_number'            => $show_results_number ? 'true' : 'false',
			'listing_type'                   => $listing_type,
			'show_sort_by_newest'            => $show_sort_by_newest ? 'true' : 'false',
			'show_sort_by_popular'           => $show_sort_by_popular ? 'true' : 'false',
			'show_sort_by_price_high_to_low' => $show_sort_by_price_high_to_low ? 'true' : 'false',
			'show_sort_by_price_low_to_high' => $show_sort_by_price_low_to_high ? 'true' : 'false',
			'show_sort_by_alphabetically'    => $show_sort_by_alphabetically ? 'true' : 'false',
			'search_form'                    => $search_form
		);

		if ( in_array( 'agency', $this->user->roles ) ) {
			$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
			if ( ! empty( $enabled ) ) {
				$agents   = array( $this->user->ID );
				$wp_users = get_users( array(
					'meta_key'     => 'myhome_agency',
					'meta_value'   => $this->user->ID,
					'meta_compare' => '=='
				) );

				foreach ( $wp_users as $user ) {
					$agents[] = $user->ID;
				}

				$atts['agents'] = $agents;
				unset( $atts['agent'] );
			}
		}

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			if ( isset( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] ) ) {
				$atts[ $attribute->get_slug() . '_show' ] = ! empty( \MyHomeCore\My_Home_Core()->settings->props[ 'mh-listing-' . $attribute->get_slug() . '_show' ] );
			} else {
				$atts[ $attribute->get_slug() . '_show' ] = true;
			}
		}

		$listing = new Listing( $atts );
		?>
        <div class="mh-listing--full-width mh-listing--horizontal-boxed">
			<?php $listing->display(); ?>
        </div>
		<?php
	}

	/**
	 * @return bool
	 */
	public function is_confirmed() {
		$is_confirmed = get_user_meta( $this->get_ID(), 'myhome_agent_confirmed', true );

		return ! empty( $is_confirmed );
	}

	/**
	 * @return bool
	 */
	public function is_agency() {
		return in_array( 'agency', $this->user->roles );
	}

	/**
	 * @return User[]
	 */
	public function get_agents() {
		if ( ! $this->is_agency() ) {
			return array();
		}

		$enabled = \MyHomeCore\My_Home_Core()->settings->get( 'agent-agency' );
		if ( empty( $enabled ) ) {
			return array();
		}

		$wp_users = get_users( array(
			'meta_key'     => 'myhome_agency',
			'meta_value'   => $this->user->ID,
			'meta_compare' => '=='
		) );

		$agents = array();
		foreach ( $wp_users as $wp_user ) {
			$agents[] = new User( $wp_user );
		}

		return $agents;
	}

	/**
	 * @return bool
	 */
	public function has_agency() {
		return isset( $this->meta['myhome_agency'] ) && ! empty( $this->meta['myhome_agency'] ) && ! empty( $this->meta['myhome_agency'][0] );
	}

	/**
	 * @return User
	 */
	public function get_agency() {
		return User::get_user_by_id( $this->meta['myhome_agency'][0] );
	}

	/**
	 * @return bool
	 */
	public function is_buyer() {
		return in_array( 'buyer', $this->user->roles );
	}

	/**
	 * @return bool
	 */
	public function is_accepted() {
		$moderate = \MyHomeCore\My_Home_Core()->settings->get( 'agent_moderation' );
		if ( empty( $moderate ) ) {
			return true;
		}

		$is_accepted = get_user_meta( $this->get_ID(), 'myhome_accepted', true );

		return ! empty( $is_accepted );
	}

	/**
	 * @return int
	 */
	public function get_listing_number() {
		$query = new WP_Query( [
			'post_type'      => 'estate',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'author'         => $this->get_ID(),
		] );

		return $query->found_posts;
	}

}