<?php

namespace MyHomeCore\Widgets;


use MyHomeCore\Core;

class Twitter_Widget extends \WP_Widget {

	/**
	 * construct
	 *
	 * Set widget name
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param N /A
	 *
	 * @return N/A
	 *
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-mh-twitter',
			'description' => esc_html__( 'Display latest tweets', 'myhome-core' ),
		);
		parent::__construct( 'myhome-twitter-widget', esc_html__( 'MH Twitter', 'myhome-core' ), $widget_ops );
	}

	/**
	 * widget
	 *
	 * Outputs the content of the widget
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param $args     (array)
	 * @param $instance (array)
	 *
	 * @return N/A
	 *
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		// Check if cache exists
		$tweets = $this->get_tweets( $instance );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$instance['title'] = apply_filters(
				'wpml_translate_single_string',
				$instance['title'],
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Twitter widget', 'myhome-core' )
			);
		}

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo myhome_filter( $before_widget );

		if ( ! empty( $title ) ) {
			echo myhome_filter( $before_title . $title . $after_title );
		}
		?>
		<div class="mh-widget-twitter">
			<?php foreach ( $tweets as $tweet ) : ?>
				<div class="tweet">
					<span><?php echo esc_html( $tweet['created_at'] ); ?> /</span> <?php echo esc_html( $tweet['text'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php

		echo myhome_filter( $after_widget );
	}

	/**
	 * form
	 *
	 * Outputs the options form on admin
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param $instance (array)
	 *
	 * @return N/A
	 *
	 */
	public function form( $instance ) {
		// prepare options
		$instance = wp_parse_args(
			(array) $instance, array(
				'title'               => '',
				'twitter_handle'      => '',
				'api_key'             => '',
				'api_secret'          => '',
				'access_token'        => '',
				'access_token_secret' => '',
				'cache_duration'      => 10,
				'tweets_number'       => 4,
				'show_retweets'       => 1
			)
		);

		$title               = $instance['title'];
		$twitter_handle      = $instance['twitter_handle'];
		$api_key             = $instance['api_key'];
		$api_secret          = $instance['api_secret'];
		$access_token        = $instance['access_token'];
		$access_token_secret = $instance['access_token_secret'];
		$cache_duration      = $instance['cache_duration'];
		$tweets_number       = $instance['tweets_number'];
		$show_retweets       = $instance['show_retweets'];

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$title = apply_filters(
				'wpml_translate_single_string',
				$title,
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Twitter widget', 'myhome-core' )
			);
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter_handle' ) ); ?>"><?php esc_html_e( 'Twitter Handle:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_handle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_handle' ) ); ?>" type="text" value="<?php echo esc_attr( $twitter_handle ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>"><?php esc_html_e( 'Consumer Key (API Key):', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'api_key' ) ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'api_secret' ) ); ?>"><?php esc_html_e( '	Consumer Secret (API Secret):', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'api_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'api_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $api_secret ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php esc_html_e( 'Access Token:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>"><?php esc_html_e( 'Access Token Secret:', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token_secret ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cache_duration' ) ); ?>"><?php esc_html_e( 'Cache duration (minutes):', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache_duration' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache_duration' ) ); ?>" type="text" value="<?php echo esc_attr( $cache_duration ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tweets_number' ) ); ?>"><?php esc_html_e( 'Number of displayed tweets', 'myhome-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tweets_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tweets_number' ) ); ?>" type="text" value="<?php echo esc_attr( $tweets_number ); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_retweets' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_retweets' ) ); ?>" value="1" <?php checked( $show_retweets, 1 ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_retweets' ) ); ?>"><?php esc_html_e( 'Show retweets', 'myhome-core' ); ?></label>
		</p>
		<?php
	}

	/**
	 * update
	 *
	 * Processing widget options on save
	 *
	 * @date   23/06/16
	 * @since  1.0.0
	 *
	 * @param $new_instance (array)
	 * @param $old_instance (array)
	 *
	 * @return $instance (array)
	 *
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                        = $old_instance;
		$instance['title']               = strip_tags( $new_instance['title'] );
		$instance['twitter_handle']      = strip_tags( $new_instance['twitter_handle'] );
		$instance['api_key']             = strip_tags( $new_instance['api_key'] );
		$instance['api_secret']          = strip_tags( $new_instance['api_secret'] );
		$instance['access_token']        = strip_tags( $new_instance['access_token'] );
		$instance['access_token_secret'] = strip_tags( $new_instance['access_token_secret'] );
		$instance['cache_duration']      = intval( $new_instance['cache_duration'] );
		$instance['tweets_number']       = intval( $new_instance['tweets_number'] );
		$instance['show_retweets']       = intval( $new_instance['show_retweets'] );

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			do_action(
				'wpml_register_single_string',
				esc_html__( 'MyHome - Widgets', 'myhome-core' ),
				esc_html__( 'Twitter widget', 'myhome-core' ),
				$instance['title']
			);
		}
		// clear cache
		delete_transient( 'mh_tweets' );

		return $instance;
	}

	/**
	 * get_tweets
	 *
	 * Grab latest tweets via twitter api call
	 *
	 * @date  23/06/16
	 * @since 1.0.0
	 *
	 * @param $instance (array)
	 *
	 * @return array
	 *
	 */
	public function get_tweets( $instance ) {
		require_once MYHOME_CORE_DIR . '/includes/libs/TwitterAPIExchange.php';

		if ( ! ( false === ( $tweets = get_transient( 'mh_tweets' ) ) ) ) {
			return $tweets;
		}

		// prepare auth data
		$settings = array(
			'consumer_key'              => $instance['api_key'],
			'consumer_secret'           => $instance['api_secret'],
			'oauth_access_token'        => $instance['access_token'],
			'oauth_access_token_secret' => $instance['access_token_secret']
		);

		// api call base on https://dev.twitter.com/rest/reference/get/statuses/user_timeline
		$url           = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield      = '?screen_name=' . $instance['twitter_handle'] . '&count=' . $instance['tweets_number'] . '&include_rts=' . $instance['show_retweets'];
		$requestMethod = 'GET';

		try {
			$twitter  = new \TwitterAPIExchange( $settings );
			$response = json_decode(
				$twitter->setGetfield( $getfield )
				        ->buildOauth( $url, $requestMethod )
				        ->performRequest()
			);
		}
		catch ( \Exception $e ) {
			echo myhome_filter( $e->getMessage() );

			return array();
		}
		$tweets = array();

		if ( isset( $response->errors ) ) {
			return $tweets;
		}

		// get only necessary data
		foreach ( $response as $tweet ) {
			array_push(
				$tweets, array(
					'text'       => $tweet->text,
					'created_at' => date( 'd.m.Y', strtotime( $tweet->created_at ) )
				)
			);
		}

		// save cache
		set_transient( 'mh_tweets', $tweets, $instance['cache_duration'] * 60 );

		return $tweets;
	}

}

