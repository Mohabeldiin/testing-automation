<?php

namespace MyHomeCore\Users;


/**
 * Class Users_Factory
 * @package MyHomeCore\Users
 */
class Users_Factory {

	/**
	 * @param int $limit
	 * @param bool $exclude_admins
	 * @param array $include
	 * @param bool $exclude_agents
	 * @param bool $exclude_agency
	 *
	 * @return array
	 * @throws \ErrorException
	 */
	public static function get_agents( $limit = - 1, $exclude_admins = true, $include = array(), $exclude_agents = false, $exclude_agency = false, $exclude_super_agents = true, $sort_by = '' ) {
		$roles = array();

		if ( ! $exclude_agents ) {
			$roles[] = 'agent';
		}

		if ( ! $exclude_super_agents ) {
			$roles[] = 'super_agent';
		}

		if ( ! $exclude_agency ) {
			$roles[] = 'agency';
		}

		if ( ! $exclude_admins ) {
			$roles[] = 'administrator';
		}

		if ( empty( $roles ) ) {
			$roles[] = 'agent';
		}

		$data = array(
			'role__in' => $roles,
			'number'   => $limit,
		);

		if ( $sort_by == 'name' ) {
			$data['orderby'] = 'display_name';
			$data['order']   = 'ASC';
		}

		$moderation = \MyHomeCore\My_Home_Core()->settings->get( 'agent_moderation' );
		if ( ! empty( $moderation ) ) {
			$data['meta_key']     = 'myhome_accepted';
			$data['meta_value']   = '1';
			$data['meta_compare'] = '==';
		}

		if ( ! empty( $include ) ) {
			$data['include'] = $include;
		}


		$users      = [];
		$users_temp = get_users( $data );

		if ( isset( $data['orderby'] ) && $data['orderby'] == 'display_name' ) {
			if ( ! empty( $include ) ) {
				$users = array_filter( $users, function ( $user ) use ( $include ) {
					return in_array( $user->ID, $include );
				} );
			}

			foreach ( $users_temp as $user ) {
				$users[] = User::get_instance( $user );
			}

			return $users;
		}

		foreach ( $users_temp as $user ) {
			if ( ! isset( $users[ $user->ID ] ) ) {
				$users[ $user->ID ] = $user;
			}
		}

		$agents = array();
		foreach ( $users as $user ) {
			$agents[] = User::get_instance( $user );
		}

		usort( $agents, function ( $a, $b ) use ( $include ) {
			$pos_a = array_search( $a->get_ID(), $include );
			$pos_b = array_search( $b->get_ID(), $include );

			return $pos_a - $pos_b;
		} );

		return $agents;
	}

	/**
	 * @return User
	 */
	public static function get_current() {
		$user_id = get_current_user_id();

		return User::get_instance( $user_id );
	}

}