<?php

if ( ! class_exists( 'BB_Weather_Widget' ) ) {

	// ICON

	class BB_Weather_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'bt_bb_weather_widget', // Base ID
				esc_html__( 'BB Weather', 'bold-builder' ), // Name
				array( 'description' => esc_html__( 'Weather widget.', 'bold-builder' ) ) // Args
			);
		}

		public function widget( $args, $instance ) {
			
			wp_enqueue_style( 'bt_bb_weather', plugin_dir_url( __FILE__ ) . 'weather_icons.css', array(), BT_BB_VERSION );
			
			$this->latitude = ! empty( $instance['latitude'] ) ? $instance['latitude'] : '';
			$this->longitude = ! empty( $instance['longitude'] ) ? $instance['longitude'] : '';
			$this->temp_unit = ! empty( $instance['temp_unit'] ) ? $instance['temp_unit'] : '';
			$this->type = ! empty( $instance['type'] ) ? $instance['type'] : '';
			$this->cache = ! empty( $instance['cache'] ) ? $instance['cache'] : '';
			
			$this->cache = intval( $this->cache );

			if ( $this->cache < 0 ) {
				$this->cache = 0;
			} else if ( $this->cache > 60 * 12 ) {
				$this->cache = 60 * 12;
			}
			
			$this->api_key = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';

			$trans_name = 'bt_bb_weather_data_' . md5( $this->latitude . $this->longitude . $this->temp_unit . $this->type . $this->cache );

			$weather_data = get_transient( $trans_name );

			if ( $weather_data === false ) {
				
				$session = false;

				if ( $this->type == 'now' ) {
					$session = curl_init( 'https://api.openweathermap.org/data/2.5/weather?lat=' . $this->latitude . '&lon=' . $this->longitude . '&units=' . $this->temp_unit . '&appid=' . $this->api_key );
				} else if ( $this->type == 'forecast12' || $this->type == 'forecast24' ) {
					$session = curl_init( 'https://api.openweathermap.org/data/2.5/forecast?lat=' . $this->latitude . '&lon=' . $this->longitude . '&units=' . $this->temp_unit . '&appid=' . $this->api_key );
				}
				
				if ( ! $session ) {
					return;
				}
				
				curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
				
				$json = curl_exec( $session );
				
				$result = json_decode( $json, true );

				if ( is_array( $result ) && ( isset( $result['weather'] ) || isset( $result['list'] ) || isset( $result['main'] ) ) ) {

					if ( $this->type == 'now' ) {

						$weather_data = array(
							'icon' => $result['weather'][0]['icon'],
							'temp' => round( $result['main']['temp'] ),
						);
						
						if ( $weather_data['temp'] == 0 ) { // -0 fix
							$weather_data['temp'] = 0;
						}
						
					} else if ( $this->type == 'forecast12' || $this->type == 'forecast24' ) {
						
						if ( $this->type == 'forecast12' ) {
							$n = 4;
						} else if ( $this->type == 'forecast24' ) {
							$n = 8;
						}

						$min_temp = 1000;
						$max_temp = -1000;
						
						$icons = array();
						$icons_int = array();
						$d_icons = 0;
						$n_icons = 0;

						$weather_data = array();
						
						for ( $i = 0; $i < $n; $i++ ) {
							
							$t = $result['list'][ $i ]['main']['temp'];
							
							if ( $t > $max_temp ) {
								$max_temp = $t;
							} else if ( $t < $min_temp ) {
								$min_temp = $t;
							}
							
							$icon = $result['list'][ $i ]['weather'][0]['icon'];
							
							$icons[] = $icon;
							$icons_int[] = intval( $icon );
							
							if ( strpos( $icon, 'd' ) ) {
								$d_icons++;
							} else if ( strpos( $icon, 'n' ) ) {
								$n_icons++;
							}
							
						}
						
						// temp
						
						$weather_data['temp_low'] = round( $min_temp );
						$weather_data['temp_high'] = round( $max_temp );
						
						if ( $weather_data['temp_low'] == 0 ) { // -0 fix
							$weather_data['temp_low'] = 0;
						}
						
						if ( $weather_data['temp_high'] == 0 ) { // -0 fix
							$weather_data['temp_high'] = 0;
						}
						
						// icon
						
						if ( in_array( '11', $icons_int ) ) {
							$weather_data['icon'] = '11d';
						} else if ( in_array( '13', $icons_int ) ) {
							$weather_data['icon'] = '13d';
						} else if ( in_array( '10', $icons_int ) ) {
							$weather_data['icon'] = '10d';
						} else if ( in_array( '9', $icons_int ) ) {
							$weather_data['icon'] = '09d';
						} else if ( in_array( '50', $icons_int ) ) {
							$weather_data['icon'] = '50d';
						} else if ( in_array( '4', $icons_int ) ) {
							$weather_data['icon'] = '04d';
						} else if ( in_array( '3', $icons_int ) ) {
							$weather_data['icon'] = '03d';
						} else {
							if ( $d_icons >= $n_icons ) {
								if ( in_array( '2', $icons_int ) ) {
									$weather_data['icon'] = '02d';
								} else if ( in_array( '1', $icons_int ) ) {
									$weather_data['icon'] = '01d';
								}
							} else {
								if ( in_array( '2', $icons_int ) ) {
									$weather_data['icon'] = '02n';
								} else if ( in_array( '1', $icons_int ) ) {
									$weather_data['icon'] = '01n';
								}
							}
						}
					}

					set_transient( $trans_name, $weather_data, $this->cache );
					
				}
			}

			if ( $weather_data !== false && isset( $weather_data['temp'] ) ) {
				if ( $this->type == 'now' ) {
					echo '<span class="btIconWidget btWidgetWithText">';
						echo '<span class="btIconWidgetIcon">';
							echo bt_bb_icon::get_html( 'wi_' . $this->get_icon_code( $weather_data['icon'] ) );
						echo '</span>';
						echo '<span class="btIconWidgetContent">';
							echo '<span class="btIconWidgetTitle">' . esc_html__( 'Now', 'bold-builder' ) . '</span>';
							echo '<span class="btIconWidgetText">' . $weather_data['temp'] . '&deg;' . ( $this->temp_unit == 'imperial' ? 'F' : 'C' ) . '</span>';
						echo '</span>';
					echo '</span>';
				} else if ( $this->type == 'forecast12' || $this->type == 'forecast24' ) {
					echo '<span class="btIconWidget">';
						echo '<span class="btIconWidgetIcon">';
							echo bt_bb_icon::get_html( 'wi_' . $this->get_icon_code( $weather_data['icon'] ) );
						echo '</span>';
						echo '<span class="btIconWidgetContent">';
							if ( $this->type == 'forecast12' ) {
								echo '<span class="btIconWidgetTitle">' . esc_html__( '12 h', 'bold-builder' ) . '</span>';
							} else if ( $this->type == 'forecast24' ) {
								echo '<span class="btIconWidgetTitle">' . esc_html__( '24 h', 'bold-builder' ) . '</span>';
							}
							echo '<span class="btIconWidgetText">' . $weather_data['temp_low'] . '/' . $weather_data['temp_high'] . '&deg;' . ( $this->temp_unit == 'imperial' ? 'F' : 'C' ) . '</span>';
						echo '</span>';
					echo '</span>';
				}
			}
		}
		
		public function get_icon_code( $code ) {
			$map = array(
				'01d' => 'f00d',
				'02d' => 'f002',
				'03d' => 'f041',
				'04d' => 'f013',
				'09d' => 'f01a',
				'10d' => 'f019',
				'11d' => 'f01e',
				'13d' => 'f01b',
				'50d' => 'f014',
				
				'01n' => 'f02e',
				'02n' => 'f086',
				'03n' => 'f041',
				'04n' => 'f013',
				'09n' => 'f01a',
				'10n' => 'f019',
				'11n' => 'f01e',
				'13n' => 'f01b',
				'50n' => 'f014',				
			);

			return $map[ $code ];
		}	

		public function form( $instance ) {
			$latitude = ! empty( $instance['latitude'] ) ? $instance['latitude'] : '';
			$longitude = ! empty( $instance['longitude'] ) ? $instance['longitude'] : '';
			$temp_unit = ! empty( $instance['temp_unit'] ) ? $instance['temp_unit'] : '';
			$type = ! empty( $instance['type'] ) ? $instance['type'] : '';
			$cache = ! empty( $instance['cache'] ) ? $instance['cache'] : '30';
			$api_key = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';
			
			?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>"><?php _e( 'Latitude:', 'bold-builder' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'latitude' ) ); ?>" type="text" value="<?php echo esc_attr( $latitude ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>"><?php _e( 'Longitude:', 'bold-builder' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'longitude' ) ); ?>" type="text" value="<?php echo esc_attr( $longitude ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'temp_unit' ) ); ?>"><?php _e( 'Temperature unit:', 'bold-builder' ); ?></label> 
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'temp_unit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'temp_unit' ) ); ?>">
						<?php
						$target_arr = array( esc_html__( 'Celsius', 'bold-builder' ) => 'metric', esc_html__( 'Fahrenheit', 'bold-builder' ) => 'imperial' );
						foreach( $target_arr as $key => $value ) {
							if ( $value == $temp_unit ) {
								echo '<option value="' . esc_attr( $value ) . '" selected>' . $key . '</option>';
							} else {
								echo '<option value="' . esc_attr( $value ) . '">' . $key . '</option>';
							}
						}
						?>
					</select>
				</p>				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type:', 'bold-builder' ); ?></label> 
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
						<?php
						$target_arr = array( esc_html__( 'Now', 'bold-builder' ) => 'now', esc_html__( 'Next 12 hours', 'bold-builder' ) => 'forecast12', esc_html__( 'Next 24 hours', 'bold-builder' ) => 'forecast24' );
						foreach( $target_arr as $key => $value ) {
							if ( $value == $type ) {
								echo '<option value="' . esc_attr( $value ) . '" selected>' . $key . '</option>';
							} else {
								echo '<option value="' . esc_attr( $value ) . '">' . $key . '</option>';
							}
						}
						?>
					</select>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>"><?php _e( 'Cache (minutes):', 'bold-builder' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cache' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cache' ) ); ?>" type="text" value="<?php echo esc_attr( $cache ); ?>">			
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>"><?php _e( 'API key:', 'bold-builder' ); ?></label> 
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'api_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'api_key' ) ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>">
					<br>
					<i><?php _e( 'Get Openweather API key here: ', 'bold-builder' ); ?></i><a href="https://openweathermap.org/appid" target="_blank">https://openweathermap.org/appid</a>
				</p>		
				
			<?php 
		}

		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['latitude'] = ( ! empty( $new_instance['latitude'] ) ) ? strip_tags( $new_instance['latitude'] ) : '';
			$instance['longitude'] = ( ! empty( $new_instance['longitude'] ) ) ? strip_tags( $new_instance['longitude'] ) : '';
			$instance['temp_unit'] = ( ! empty( $new_instance['temp_unit'] ) ) ? strip_tags( $new_instance['temp_unit'] ) : '';
			$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
			$instance['cache'] = ( ! empty( $new_instance['cache'] ) ) ? strip_tags( $new_instance['cache'] ) : '';
			$instance['api_key'] = ( ! empty( $new_instance['api_key'] ) ) ? strip_tags( $new_instance['api_key'] ) : '';
			
			return $instance;
		}
	}
}