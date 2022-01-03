<?php

namespace MyHomeCore\Estates;

use MyHomeCore\Attributes\Offer_Type_Attribute;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Estates\Filters\Estate_Filter;
use MyHomeCore\Estates\Filters\Estate_ID_Filter;
use MyHomeCore\Estates\Filters\Estate_Keyword_Filter;
use MyHomeCore\Estates\Filters\Estate_Offer_Type_Filter;
use MyHomeCore\Estates\Filters\Estate_Price_Filter;
use MyHomeCore\Estates\Prices\Currencies;
use MyHomeCore\Terms\Term;
use MyHomeCore\Terms\Term_Factory;

/**
 * Class Estate_Factory
 *
 * @package MyHomeCore\Estates
 */
class Estate_Factory {

	const NO_LIMIT = - 1;
	const ORDER_BY_TITLE_ASC = 'titleASC';
	const ORDER_BY_TITLE_DESC = 'titleDESC';
	const ORDER_BY_NEWEST = 'newest';
	const ORDER_BY_PRICE_HIGH_TO_LOW = 'priceHighToLow';
	const ORDER_BY_PRICE_LOW_TO_HIGH = 'priceLowToHigh';
	const ORDER_BY_POPULAR = 'popular';
	const ORDER_BY_RANDOM = 'random';
	const ORDER_BY_ID_DESC = 'idDESC';
	const ORDER_BY_POST_IN = 'post__in';

	/**
	 * @var int
	 */
	private $found_number = 0;

	/**
	 * @var string
	 */
	private $currency = 'any';

	private $price_values = array();

	private $sort_by = '';

	/**
	 * @var array
	 */
	private $args = array(
		'post_type'           => 'estate',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => 12,
		'page'                => 1,
		'tax_query'           => array(
			'relation' => 'AND'
		),
		'meta_query'          => array(
			'relation' => 'AND'
		)
	);

	/**
	 * @var Estate_Filter[]
	 */
	private $filters = array();

	/**
	 * @var bool
	 */
	private $isSearch = false;

	/**
	 * Estate_Factory constructor.
	 *
	 * @param array $args
	 * @param bool $isSearch
	 */
	public function __construct( $args = array(), $isSearch = false ) {
		$this->args     = array_merge( $this->args, $args );
		$this->isSearch = $isSearch;
	}

	/**
	 * @param Estate_Filter $estate_filter
	 */
	public function add_filter( Estate_Filter $estate_filter ) {
		if ( $estate_filter instanceof Estate_Price_Filter ) {
			$this->price_values[ $estate_filter->get_compare() ] = $estate_filter->get_value();
		}

		$this->filters[] = $estate_filter;
	}

	/**
	 * @param mixed $page
	 */
	public function set_page( $page ) {
		$this->args['page'] = intval( $page );
	}

	/**
	 * @param $date
	 */
	public function set_published_after( $date ) {
		$this->args['date_query'] = array(
			array( 'after' => $date )
		);
	}

	/**
	 * @param array $status
	 */
	public function set_status( array $status ) {
		$this->args['post_status'] = $status;
	}

	/**
	 * @param $sort_by
	 * @param  $offer_type
	 */
	public function set_sort_by( $sort_by, $offer_type = null ) {
		$this->sort_by = $sort_by;

		$key = '';

		if ( $sort_by == Estate_Factory::ORDER_BY_PRICE_HIGH_TO_LOW
		     || $sort_by == Estate_Factory::ORDER_BY_PRICE_LOW_TO_HIGH
		) {
			if ( ! empty( \MyHomeCore\My_Home_Core()->currency ) && \MyHomeCore\My_Home_Core()->currency != 'any' ) {
				$key = \MyHomeCore\My_Home_Core()->currency;
			} else {
				$currency = Currencies::get_current();
				$key      = ! empty( $currency ) ? $currency->get_key() : 'price';
			}
		}

		if ( $offer_type instanceof Term ) {
			$key .= '_offer_' . $offer_type->get_ID();
		}

		if ( $sort_by == Estate_Factory::ORDER_BY_PRICE_HIGH_TO_LOW ) {
			if ( Price_Attribute::is_range() ) {
				$key .= '_from';
			}
			$this->args['orderby']  = 'meta_value_num';
			$this->args['meta_key'] = 'estate_attr_' . $key;
			$this->args['order']    = 'DESC';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_PRICE_LOW_TO_HIGH ) {
			if ( Price_Attribute::is_range() ) {
				$key .= '_from';
			}
			$this->args['orderby']  = 'meta_value_num';
			$this->args['meta_key'] = 'estate_attr_' . $key;
			$this->args['order']    = 'ASC';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_POPULAR ) {
			$this->args['orderby']      = 'meta_value_num';
			$this->args['meta_key']     = 'estate_views';
			$this->args['order']        = 'DESC';
			$this->args['meta_query'][] = array(
				'key'     => 'myhome_estate_views',
				'value'   => '',
				'compare' => 'NOT EXISTS'
			);
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_TITLE_ASC ) {
			$this->args['order']   = 'ASC';
			$this->args['orderby'] = 'title';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_TITLE_DESC ) {
			$this->args['order']   = 'DESC';
			$this->args['orderby'] = 'title';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_RANDOM ) {
			$this->args['orderby'] = 'rand';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_ID_DESC ) {
			$this->args['orderby'] = 'ID';
			$this->args['order']   = 'DESC';
		} elseif ( $sort_by == Estate_Factory::ORDER_BY_POST_IN ) {
			$this->args['orderby'] = 'post__in';
		} else {
			$this->args['orderby'] = array( 'date' => 'DESC', 'ID' => 'DESC' );
		}
	}

	/**
	 * @param int $limit
	 */
	public function set_limit( $limit ) {
		$limit = intval( $limit );
		if ( empty( $limit ) ) {
			$limit = 10;
		}
		$this->args['posts_per_page'] = $limit;
	}

	public function set_featured_only() {
		$this->args['meta_query'][] = array(
			'key'     => 'estate_featured',
			'value'   => '1',
			'compare' => '=='
		);
	}

	/**
	 * @param array $ids
	 */
	public function set_estates__in( $ids = array() ) {
		if ( empty( $this->args['post__in'] ) ) {
			$this->args['post__in'] = $ids;

			return;
		}

		$this->args['post__in'] = array_filter( $this->args['post__in'], function ( $id ) use ( $ids ) {
			return in_array( $id, $ids );
		} );
	}

	/**
	 * @param array $ids
	 */
	public function set_estates__not_in( $ids = array() ) {
		$this->args['post__not_in'] = $ids;
	}

	/**
	 * @param string $currency
	 */
	public function set_currency( $currency ) {
		$this->currency = $currency;
	}

	/**
	 * @param int $user_id
	 */
	public function set_user_id( $user_id ) {
		$this->args['author'] = intval( $user_id );
	}

	/**
	 * @param array $user_ids
	 */
	public function set_users( $user_ids ) {
		$this->args['author__in'] = $user_ids;
	}

	public function set_keyword( $keyword ) {
		$this->args['s'] = $keyword;
	}

	/**
	 * @return int
	 */
	private function get_offset() {
		return $this->args['page'] * $this->args['posts_per_page'] - $this->args['posts_per_page'];
	}

	private function check_post_in() {
		if ( ! isset( $this->args['post__not_in'] ) || empty( $this->args['post__not_in'] ) ) {
			return;
		}

		if ( ! isset( $this->args['post__in'] ) || empty( $this->args['post__in'] ) ) {
			return;
		}

		$this->args['post__in'] = array_filter( $this->args['post__in'], function ( $id ) {
			return ! in_array( $id, $this->args['post__not_in'] );
		} );
	}

	/**
	 * @return Estates
	 */
	public function get_results() {
		$this->args['offset']      = $this->get_offset();
		$this->args['tax_query'][] = Offer_Type_Attribute::get_exclude();

		if ( ! empty( \MyHomeCore\My_Home_Core()->currency ) && \MyHomeCore\My_Home_Core()->currency != 'any'
		     && \MyHomeCore\My_Home_Core()->currency != 'undefined'
		) {
			$currency = Currencies::get_current();
			$this->set_estates__in( $currency->get_estate_ids() );
		}

		$selected_offer_types = array();
		foreach ( $this->filters as $filter ) {
			if ( $filter instanceof Estate_Offer_Type_Filter ) {
				$selected_offer_types = $filter->get_selected_offer_types();
			}
		}

//		if ( empty( $selected_offer_types ) ) {
//			foreach ( Term_Factory::get_offer_types() as $offer_type ) {
//				if ( $offer_type->specify_price() ) {
//					$selected_offer_types[] = $offer_type;
//				}
//			}
//		}

		foreach ( $selected_offer_types as $offer_type ) {
			if ( $offer_type->specify_price() ) {
				$this->set_sort_by( $this->sort_by, $offer_type );
				break;
			}
		}

		foreach ( $this->filters as $filter ) {
			if ( $filter instanceof Estate_Price_Filter ) {
				$filter->set_selected_offer_types( $selected_offer_types );
				$estate_ids = $filter->get_arg( $this->price_values );
				print_r($estate_ids);
				if ( empty( $estate_ids ) ) {
					$this->args['post__in'] = array();
					break;
				} else {
					$this->set_estates__in( $estate_ids );
				}
			} elseif ( $filter instanceof Estate_Keyword_Filter || $filter instanceof Estate_ID_Filter ) {
				$this->args[ $filter->get_type() ] = $filter->get_arg();
			} elseif ( is_array( $this->args[ $filter->get_type() ] ) ) {
				$this->args[ $filter->get_type() ][] = $filter->get_arg();
			} else {
				$this->args[ $filter->get_type() ] = $filter->get_arg();
			}
		}

		if ( isset( $this->args['post__in'] ) && empty( $this->args['post__in'] ) ) {
			$this->found_number = 0;

			return new Estates();
		}

		$this->check_post_in();

		$estates = new Estates();
		$args    = apply_filters( 'myhome_query_properties', $this->args );

		if ( $this->isSearch ) {
			$args = apply_filters( 'myhome_search_args', $args );
		}

		if ( isset( $args['tax_query'][0] ) && empty( $args['tax_query'][0] ) ) {
			unset( $args['tax_query'][0] );
		}

		$query = new \WP_Query( $args );

		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$estates->add( new Estate( $post ) );
		}
		$this->found_number = $query->found_posts;

		return $estates;
	}

	/**
	 * @return int
	 */
	public function get_found_number() {
		return intval( $this->found_number );
	}

	/**
	 * @param array $args
	 *
	 * @return Estates
	 */
	public static function get( $args = array() ) {
		$args = array_merge(
			array(
				'post_type' => 'estate'
			), $args
		);

		$estates = new Estates();
		$query   = new \WP_Query( $args );
		$posts   = $query->posts;
		foreach ( $posts as $post ) {
			$estates->add( new Estate( $post ) );
		}

		return $estates;
	}

	/**
	 * @param int $user_id
	 * @param array $status
	 *
	 * @return Estates
	 */
	public
	static function get_from_user(
		$user_id,
		$status = array()
	) {
		$estate_factory = new Estate_Factory();
		$estate_factory->set_user_id( $user_id );
		$estate_factory->set_status( $status );
		$estate_factory->set_limit( Estate_Factory::NO_LIMIT );

		return $estate_factory->get_results();
	}

}