<?php

if ( ! function_exists( 'bt_bb_get_twitter_data' ) ) {
	
	function bt_bb_get_twitter_data( $number, $cache, $cache_id, $username, $consumer_key, $consumer_secret, $access_token, $access_token_secret ) {
		
		if ( $number < 1 ) {
			$number = 5;
		} else if ( $number > 30 ) {
			$number = 30;
		}

		if ( $cache == 0 || $cache < 0 ) {
			$cache = 0;
		} else if ( $cache > 720 ) {
			$cache = 720;
		}
		
		$trans_name = 'bt_bb_tweets_' . $cache_id;
		
		if ( $cache == 0 ) {
			delete_transient( $trans_name );
		}

		if ( false == ( $twitter_data = unserialize( base64_decode( get_transient( $trans_name ) ) ) ) ) {
		//if ( false == false ) {
			require_once( 'twitteroauth.php' );
			$twitter_connection = new BT_BB\TwitterOAuth( $consumer_key, $consumer_secret, $access_token, $access_token_secret );
			if ( !preg_match( '/#/', $username ) ) {
				$twitter_data = $twitter_connection->get(
					'statuses/user_timeline',
					array(
						'screen_name'		=> $username,
						'count'				=> $number + 1,
						'exclude_replies'	=> false
					)
				);
			} else {
				$twitter_data = $twitter_connection->get(
					'search/tweets',
					array(
						'q'					=> $username,
						'count'				=> $number + 1,
						'exclude_replies'	=> false
					)
				);
				if ( isset( $twitter_data->statuses ) ) {
					$twitter_data = $twitter_data->statuses;
				}
			}

			if ( $twitter_connection->http_code != 200 ) {
				$twitter_data = unserialize( base64_decode( get_transient( $trans_name ) ) );
			}

			if ( $cache > 0 ) {
				set_transient( $trans_name, base64_encode( serialize( $twitter_data ) ), 60 * $cache );
			}
			
		}
		
                if ( is_array($twitter_data) ) {
                    if ( count( $twitter_data ) > $number ) $twitter_data = array_slice( $twitter_data, 0, $number );
                }
		
		return $twitter_data;
	}
}

if ( ! class_exists( 'BB_Twitter_Widget' ) ) {
	
	// TWITTER	
	
	class BB_Twitter_Widget extends WP_Widget {
	
		function __construct() {
			parent::__construct(
				'bt_bb_twitter_widget', // Base ID
				esc_html__( 'BB Twitter (deprecated)', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Twitter feed.', 'bold-builder' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
			
			$number = intval( trim( $instance['number'] ) );
			$cache = intval( trim( $instance['cache'] ) );	

			$this->number = $number;
			$this->cache = $cache;
			$this->cache_id = $instance['cache_id'];
			$this->username = trim( $instance['username'] );
			$this->consumer_key = trim( $instance['consumer_key'] );
			$this->consumer_secret = trim( $instance['consumer_secret'] );
			$this->access_token = trim( $instance['access_token'] );
			$this->access_token_secret = trim( $instance['access_token_secret'] );
			
			if ( $this->number == '' || $this->username == '' || $this->consumer_key == '' || $this->consumer_secret == '' || $this->access_token == '' || $this->access_token_secret == '' ) {
				return;
			}			
		
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			$twitter_data = bt_bb_get_twitter_data( $this->number, $this->cache, $this->cache_id, $this->username, $this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret );
			
			echo '<div class="recentTweets">';

			if ( is_array( $twitter_data ) ) {
				$index = 0;
				foreach ( $twitter_data as $data ) {
					$user =  $data->user->screen_name;
					$profile_link = 'https://twitter.com/' . $user ;
					$link = 'https://twitter.com/' . $user . '/status/' . $data->id_str;
					
					$text = mb_convert_encoding( utf8_encode( $data->text ), 'HTML-ENTITIES', 'UTF-8' );

					$time = human_time_diff( strtotime( $data->created_at ) );

					echo '<small><a href="' . esc_url( $link ) . '">@' . $user . ' - ' . $time . '</a></small>';
					echo '<p>' . BB_Twitter_Widget::parse( $data->text ) . '</p>';
					$index++;
					if ( $index > $number - 1 ) break; 
				}
			}
			
			echo '</div>';
				
			echo $args['after_widget'];
		}
		
		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Twitter', 'bold-builder' );
			$number = ! empty( $instance['number'] ) ? $instance['number'] : '5';
			$cache = ! empty( $instance['cache'] ) ? $instance['cache'] : '0';
			$username = ! empty( $instance['username'] ) ? $instance['username'] : '';
			$consumer_key = ! empty( $instance['consumer_key'] ) ? $instance['consumer_key'] : '';
			$consumer_secret = ! empty( $instance['consumer_secret'] ) ? $instance['consumer_secret'] : '';
			$access_token = ! empty( $instance['access_token'] ) ? $instance['access_token'] : '';
			$access_token_secret = ! empty( $instance['access_token_secret'] ) ? $instance['access_token_secret'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php _e( 'Number of tweets:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php _e( 'Username  (or #hashtag):', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"><?php _e( 'Cache (minutes):', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="text" value="<?php echo esc_attr( $cache ); ?>">			
			</p>		
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>"><?php _e( 'Consumer key:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_key' ) ); ?>" type="text" value="<?php echo esc_attr( $consumer_key ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>"><?php _e( 'Consumer secret:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $consumer_secret ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php _e( 'Access token:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token ); ?>">			
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>"><?php _e( 'Access token secret:', 'bold-builder' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token_secret' ) ); ?>" type="text" value="<?php echo esc_attr( $access_token_secret ); ?>">			
			</p>			
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
			$instance['username'] = ( ! empty( $new_instance['username'] ) ) ? strip_tags( $new_instance['username'] ) : '';
			$instance['cache'] = ( ! empty( $new_instance['cache'] ) ) ? strip_tags( $new_instance['cache'] ) : '';
			$instance['cache_id'] = ( ! empty( $new_instance['cache_id'] ) ) ? strip_tags( $new_instance['cache_id'] ) : uniqid();
			$instance['consumer_key'] = ( ! empty( $new_instance['consumer_key'] ) ) ? strip_tags( $new_instance['consumer_key'] ) : '';
			$instance['consumer_secret'] = ( ! empty( $new_instance['consumer_secret'] ) ) ? strip_tags( $new_instance['consumer_secret'] ) : '';
			$instance['access_token'] = ( ! empty( $new_instance['access_token'] ) ) ? strip_tags( $new_instance['access_token'] ) : '';
			$instance['access_token_secret'] = ( ! empty( $new_instance['access_token_secret'] ) ) ? strip_tags( $new_instance['access_token_secret'] ) : '';

			return $instance;
		}
		
		static function parse( $text ) {
			$text = preg_replace( '/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', '<a href="$1" class="twitter-link">$1</a>', $text );
			$text = preg_replace( '/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i', '<a href="http://$1" class="twitter-link">$1</a>', $text );

			$text = preg_replace( '/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i', '<a href="mailto://$1" class="twitter-link">$1</a>', $text );

			$text = preg_replace( '/([\.|\,|\:|\|\|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', '$1<a href="https://twitter.com/hashtag/$2" class="twitter-link">#$2</a>$3 ', $text );
			
			$text = preg_replace( '/([\.|\,|\:|\|\|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', '$1<a href="https://twitter.com/$2" class="twitter-user">@$2</a>$3 ', $text );			
			
			return $text;
		}
	}
}