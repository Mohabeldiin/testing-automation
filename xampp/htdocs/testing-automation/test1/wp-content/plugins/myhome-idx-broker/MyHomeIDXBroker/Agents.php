<?php

namespace MyHomeIDXBroker;


/**
 * Class Agents
 * @package MyHomeIDXBroker
 */
class Agents {

	const FIELD_ID = 'idx_broker_user_id';
	const FIELD_LISTING_ID = 'idx_broker_user_listing_id';

	public function import() {
		$api    = new Api();
		$agents = $api->get_agents();

		if ( empty( $agents ) ) {
			return;
		}

		foreach ( $agents as $agent ) {
			if ( isset( $agent->agentEmail ) ) {
				$email = $agent->agentEmail;
			} else {
				$email = '';
			}

			$user = $this->exists( $agent->agentID, $email );
			if ( $user ) {
				$idxAgent = new Idx_Broker_Agent( $user );
				$idxAgent->set_idx_id( $agent->agentID );
				update_user_meta( $idxAgent->get_ID(), 'idx_url', $agent->agentBioURL );
				continue;
			}

			$this->create( $agent );
		}
	}

	/**
	 * @param $agent_idx_id
	 * @param $email
	 *
	 * @return \WP_User|false
	 */
	public function exists( $agent_idx_id, $email ) {
		$users = get_users(
			array(
				'meta_key'   => Agents::FIELD_ID,
				'meta_value' => $agent_idx_id
			)
		);

		if ( count( $users ) > 0 ) {
			return $users[0];
		}

		if ( empty( $email ) ) {
			return false;
		}

		return get_user_by_email( $email );
	}

	/**
	 * @param $agent_data
	 *
	 * @return bool|int
	 */
	public function create( $agent_data ) {
		if ( ! isset( $agent_data->agentDisplayName ) || empty( $agent_data->agentDisplayName ) ) {
			return false;
		}

		$wp_user = get_user_by( 'email', $agent_data->agentEmail );
		if ( $wp_user !== false ) {
			update_user_meta( $wp_user->ID, Agents::FIELD_ID, $agent_data->agentID );
			update_user_meta( $wp_user->ID, Agents::FIELD_LISTING_ID, $agent_data->listingAgentID );

			return $wp_user->ID;
		}

		if ( isset( $agent_data->agentEmail ) && ! empty( $agent_data->agentEmail ) ) {
			$user_id = wp_create_user(
				$agent_data->agentEmail,
				wp_generate_password( $length = 12, $include_standard_special_chars = false ),
				$agent_data->agentEmail
			);
		} else {
			$user_id = wp_insert_user( array(
				'user_login' => $agent_data->agentDisplayName,
				'user_pass'  => wp_generate_password( $length = 12, $include_standard_special_chars = false )
			) );
		}

		if ( is_wp_error( $user_id ) ) {
			return false;
		}

		if ( isset( $agent_data->agentCellPhone ) ) {
			update_user_meta( $user_id, 'agent_phone', $agent_data->agentCellPhone );
		}

		if ( isset( $agent_data->agentBioURL ) ) {
			update_user_meta( $user_id, 'idx_url', $agent_data->agentBioURL );
		}


		if ( isset( $agent_data->agentPhotoURL ) ) {
			$image = $agent_data->agentPhotoURL;
			$get   = wp_remote_get( $image, [ 'sslverify' => false ] );
			$type  = wp_remote_retrieve_header( $get, 'content-type' );
			$name  = basename( $image );
			if ( $type == 'image/jpeg' && ( strpos( 'jpg', $image ) === false && strpos( 'jpeg', $image ) == false ) ) {
				$name .= '.jpg';
			}
			$mirror     = wp_upload_bits( $name, '', wp_remote_retrieve_body( $get ) );
			$attachment = array(
				'post_title'     => basename( $image ),
				'post_mime_type' => $type
			);

			$attachment_id = wp_insert_attachment( $attachment, $mirror['file'] );
			if ( ! is_wp_error( $attachment_id ) ) {
				update_user_meta( $user_id, 'agent_image', $attachment_id );

				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $mirror['file'] );
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
			}
		}

		if ( isset( $agent_data->bioDetails ) && ! empty( $agent_data->bioDetails ) ) {
			wp_update_user( [
				'ID'          => $user_id,
				'description' => $agent_data->bioDetails
			] );
		}

		$user_data = array(
			'ID'   => $user_id,
			'role' => 'agent'
		);

		if ( isset( $agent_data->agentFirstName ) && ! empty( $agent_data->agentFirstName ) ) {
			$user_data['first_name'] = $agent_data->agentFirstName;
		}

		if ( isset( $agent_data->agentLastName ) && ! empty( $agent_data->agentLastName ) ) {
			$user_data['last_name'] = $agent_data->agentLastName;
		}

		if ( isset( $agent_data->agentEmail ) && ! empty( $agent_data->agentEmail ) ) {
			$user_data['user_email'] = $agent_data->agentEmail;
		}

		$user_data['display_name'] = $agent_data->agentDisplayName;

		wp_update_user( $user_data );
		update_user_meta( $user_id, Agents::FIELD_ID, $agent_data->agentID );
		update_user_meta( $user_id, Agents::FIELD_LISTING_ID, $agent_data->listingAgentID );

		if ( isset( $agent_data->agentCellPhone ) && ! empty( $agent_data->agentCellPhone ) ) {
			update_user_meta( $user_id, 'agent_phone', $agent_data->agentCellPhone );
		}

		return $user_id;
	}

	/**
	 * @return Idx_Broker_Agent[]
	 */
	public static function get() {
		$agents = array();
		$users  = get_users();

		foreach ( $users as $user ) {
			$agents[] = new Idx_Broker_Agent( $user );
		}

		return $agents;
	}

}