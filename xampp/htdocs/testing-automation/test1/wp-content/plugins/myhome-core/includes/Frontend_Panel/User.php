<?php

namespace MyHomeCore\Frontend_Panel;


use Hybridauth\Exception\Exception;
use MyHomeCore\Estates\Estate;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Payments\Payments;

/**
 * Class User
 *
 * @package Frontend_Panel
 */
class User
{

    /**
     * @var \WP_User
     */
    private $user;

    /**
     * @var array
     */
    private $meta;

    /**
     * User constructor.
     *
     * @param  \WP_User  $user
     */
    public function __construct(\WP_User $user)
    {
        $this->user = $user;
        $this->meta = get_user_meta($user->ID);
    }

    /**
     * @return \WP_User
     */
    public function get_wp_user()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function get_ID()
    {
        return $this->user->ID;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return apply_filters('myhome_user_name', $this->user->user_nicename, $this->user);
    }

    /**
     * @return array
     */
    public function get_agents()
    {
        $agents   = array();
        $wp_users = get_users(array(
            'meta_key'     => 'myhome_agency',
            'meta_value'   => $this->get_ID(),
            'meta_compare' => '=='
        ));

        foreach ($wp_users as $user) {
            /* @var  $user \WP_User */
            $agents[] = array(
                'ID'    => $user->ID,
                'name'  => $user->display_name,
                'url'   => $user->user_url,
                'email' => $user->user_email
            );
        }

        return $agents;
    }

    public function get_invites()
    {
        $invites = get_user_meta($this->get_ID(), 'myhome_agency_invites', true);
        if (empty($invites) || ! is_array($invites)) {
            return array();
        }

        $wp_users = get_users(array(
            'include' => $invites
        ));

        $agencies = array();
        foreach ($wp_users as $agency) {
            /* @var  $agency \WP_User */
            $agencies[] = array(
                'ID'   => $agency->ID,
                'name' => $agency->display_name,
                'url'  => $agency->user_url
            );
        }

        return $agencies;
    }

    /**
     * @return \string
     */
    public function get_invite_code()
    {
        $invite_code = get_user_meta($this->get_ID(), 'myhome_agency_invite_code', true);
        if (empty($invite_code)) {
            update_user_meta($this->get_ID(), 'myhome_agency_invite_code', $this->generate_invite_code());
        }

        return $invite_code;
    }

    /**
     * @return \string
     */
    public function generate_invite_code()
    {
        return md5('myhome_invite_code_'.time().'_'.$this->get_ID());
    }

    /**
     * @return array
     */
    public function get_data()
    {
        $user = new \MyHomeCore\Users\User($this->user);
        if ($user->has_image()) {
            $image_url = wp_get_attachment_image_url($user->get_image_id(), 'myhome-square-s');
        } else {
            $image_url = '';
        }

        $data = array(
            'ID'           => $this->user->ID,
            'name'         => $this->user->user_login,
            'display_name' => $this->user->display_name,
            'email'        => $this->user->user_email,
            'fields'       => $user->get_fields()->get_data(),
            'social'       => $user->get_social_data(),
            'phone'        => $user->get_phone(),
            'image_id'     => $user->get_image_id(),
            'image_url'    => $image_url,
            'properties'   => array(),
            'favorite'     => array(),
            'roles'        => $this->user->roles,
            'link'         => get_author_posts_url($this->user->ID),
            'payments'     => Payments::is_enabled()
        );

        if (Payments::is_enabled()) {
            $data['package_properties'] = intval(get_user_meta($this->get_ID(), 'package_properties', true));
            $data['package_featured']   = intval(get_user_meta($this->get_ID(), 'package_featured', true));
        }

        if (in_array('agency', $this->user->roles)) {
            $data['account_type'] = 'agency';
            $data['agents']       = $this->get_agents();
            $data['invite_code']  = $this->get_invite_code();
        } elseif (in_array('agent', $this->user->roles)) {
            $data['account_type'] = 'agent';
            $data['invites']      = $this->get_invites();
            $data['has_agency']   = $user->has_agency();

            if ($user->has_agency()) {
                $agency         = $user->get_agency();
                $data['agency'] = array(
                    'name' => $agency->get_name(),
                    'id'   => $agency->get_ID()
                );
            }
        } elseif (in_array('buyer', $this->user->roles)) {
            $data['account_type'] = 'buyer';
        } elseif (in_array('super_agent', $this->user->roles)) {
            $data['account_type'] = 'super_agent';
            $data['invites']      = $this->get_invites();
            $data['has_agency']   = $user->has_agency();

            if ($user->has_agency()) {
                $agency         = $user->get_agency();
                $data['agency'] = array(
                    'name' => $agency->get_name(),
                    'id'   => $agency->get_ID()
                );
            }
        }

	    $all = \MyHomeCore\My_Home_Core()->settings->get( 'agent-all' );
	    $all = apply_filters( 'myhome_panel_current_user_can_edit_all_properties', $all );
	    if ( in_array( 'buyer', $this->user->roles ) ) {
		    $all = false;
	    }

	    $estates_factory = new Estate_Factory();
	    if ( empty( $all ) && ! in_array( 'super_agent', $this->user->roles ) && ! in_array( 'administrator', $this->user->roles ) ) {
		    $estates_factory->set_user_id( $this->user->ID );
	    }
	    if ( isset( $data['account_type'] ) && $data['account_type'] == 'agency' ) {
		    $ids = array();
		    foreach ( $this->get_agents() as $agent ) {
			    $ids[] = $agent['ID'];
		    }
		    if ( ! empty( $ids ) ) {
			    $estates_factory->set_users( $ids );
		    }
	    }
	    $estates_factory->set_status( array( 'any' ) );
	    $estates_factory->set_limit( - 1 );
	    $estates_factory->set_sort_by( Estate_Factory::ORDER_BY_ID_DESC );
	    $expire = intval( \MyHomeCore\My_Home_Core()->settings->get( 'frontend-properties_expire' ) );

	    foreach ( $estates_factory->get_results() as $estate ) {
		    $property = array(
			    'ID'                => $estate->get_ID(),
			    'name'              => $estate->get_name(),
			    'link'              => $estate->is_pending() ? $estate->get_preview_link() : $estate->get_link(),
			    'status'            => $estate->get_status_formatted(),
			    'created_at_string' => get_the_date( '', $estate->get_ID() ),
			    'created_at_value'  => strtotime( $estate->get_publish_date() ),
		    );

		    if ( ! empty( $expire ) ) {
			    $property['expire'] = $estate->get_expire();
		    }

		    if ( $estate->has_image() ) {
			    $property['image'] = wp_get_attachment_image_url( $estate->get_image_id(), 'myhome-standard-s' );
		    }

		    $data['properties'][] = $property;
	    }

        $favorite_properties = get_user_meta($user->get_ID(), 'myhome_favorite', true);
        if ( ! is_array($favorite_properties)) {
            $favorite_properties = array();
        }

        foreach ($favorite_properties as $property_id) {
            $post = get_post($property_id);
            if (is_null($post)) {
                continue;
            }
            $property           = new Estate($post);
            $data['favorite'][] = $property->get_data();
        }

        $searches = get_user_meta($user->get_ID(), 'myhome_searches', true);
        if ( ! is_array($searches)) {
            $searches = array();
        }
        $data['searches'] = array();

        foreach ($searches as $search) {
            $data['searches'][] = $search;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function is_confirmed()
    {
        if (empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-registration'])
            || empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_confirmation'])
        ) {
            return true;
        }

        $is_confirmed = get_user_meta($this->user->ID, 'myhome_agent_confirmed', true);

        return ! empty($is_confirmed);
    }

    public function get_properties_data($page = 1, $order_by = Estate_Factory::ORDER_BY_ID_DESC)
    {
        $account_type = 'buyer';
        $properties   = array();

        if (in_array('agency', $this->user->roles)) {
            $account_type = 'agency';
        } elseif (in_array('agent', $this->user->roles)) {
            $account_type = 'agent';
        } elseif (in_array('buyer', $this->user->roles)) {
            $account_type = 'buyer';
        } elseif (in_array('super_agent', $this->user->roles)) {
            $account_type = 'super_agent';
        }

        $all = \MyHomeCore\My_Home_Core()->settings->get('agent-all');
        $all = apply_filters('myhome_panel_current_user_can_edit_all_properties', $all);
        if (in_array('buyer', $this->user->roles)) {
            $all = false;
        }

        $estates_factory = new Estate_Factory();
        if (empty($all) && ! in_array('super_agent', $this->user->roles)
            && ! in_array('administrator', $this->user->roles)
        ) {
            $estates_factory->set_user_id($this->user->ID);
        }
        if ($account_type == 'agency') {
            $ids = array();
            foreach ($this->get_agents() as $agent) {
                $ids[] = $agent['ID'];
            }
            if ( ! empty($ids)) {
                $estates_factory->set_users($ids);
            }
        }
        $estates_factory->set_status(array('any'));
        $estates_factory->set_limit(10);
        $estates_factory->set_page($page);
        $estates_factory->set_sort_by($order_by);
        $expire = intval(\MyHomeCore\My_Home_Core()->settings->get('frontend-properties_expire'));

        foreach ($estates_factory->get_results() as $estate) {
            $property = array(
                'ID'                => $estate->get_ID(),
                'name'              => $estate->get_name(),
                'link'              => $estate->is_pending() ? $estate->get_preview_link() : $estate->get_link(),
                'status'            => $estate->get_status_formatted(),
                'created_at_string' => get_the_date('', $estate->get_ID()),
                'created_at_value'  => strtotime($estate->get_publish_date()),
                'image'             => ''
            );

            if ( ! empty($expire)) {
                $property['expire'] = $estate->get_expire();
            }

            if ($estate->has_image()) {
                $property['image'] = wp_get_attachment_image_url($estate->get_image_id(), 'myhome-standard-s');
            }

            $properties[] = $property;
        }

        return [
            'properties' => $properties,
            'total'      => $estates_factory->get_found_number()
        ];
    }

    /**
     * @return User
     */
    public static function get_current()
    {
        $wp_user = wp_get_current_user();

        return new User($wp_user);
    }

    /**
     * @param $login
     *
     * @return bool|User
     */
    public static function get_user($login)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $wp_user = get_user_by('email', $login);
        } else {
            $wp_user = get_user_by('login', $login);
        }

        if ( ! $wp_user instanceof \WP_User) {
            return false;
        }

        return new User($wp_user);
    }

    /**
     * @param  \string  $user
     *
     * @return bool
     */
    public static function exists($user)
    {
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $wp_user = get_user_by('email', $user);
        } else {
            $wp_user = get_user_by('login', $user);
        }

        return ! is_wp_error($wp_user) && $wp_user;
    }

    /**
     * @return bool
     */
    public function has_available_listings()
    {
        $listings = intval(get_user_meta($this->get_ID(), 'package_properties', true));

        return $listings > 0;
    }

    public function charge_listing()
    {
        $listings = intval(get_user_meta($this->get_ID(), 'package_properties', true));
        if ($listings > 0) {
            $listings--;
        }
        update_user_meta($this->get_ID(), 'package_properties', $listings);
    }

    /**
     * @return bool
     */
    public function has_available_featured_listings()
    {
        $listings = intval(get_user_meta($this->get_ID(), 'package_featured', true));

        return $listings > 0;
    }

    public function charge_featured_listing()
    {
        $listings = intval(get_user_meta($this->get_ID(), 'package_featured', true));
        if ($listings > 0) {
            $listings--;
        }
        update_user_meta($this->get_ID(), 'package_featured', $listings);
    }

    /**
     * @return int
     */
    public function get_available_listings_number()
    {
        return intval(get_user_meta($this->get_ID(), 'package_properties', true));
    }

    /**
     * @return bool
     */
    public function is_accepted()
    {
        $moderate = \MyHomeCore\My_Home_Core()->settings->get('agent_moderation');
        if (empty($moderate)) {
            return true;
        }

        $is_accepted = get_user_meta($this->get_ID(), 'myhome_accepted', true);

        return ! empty($is_accepted);
    }

}