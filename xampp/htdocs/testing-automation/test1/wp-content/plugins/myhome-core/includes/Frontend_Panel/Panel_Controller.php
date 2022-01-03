<?php

namespace MyHomeCore\Frontend_Panel;


use MyHomeCore\Panel\Panel_Notifications;
use MyHomeCore\Payments\Payments;
use MyHomeCore\Users\Agents_Manager;
use MyHomeCore\Users\Fields\Field;
use MyHomeCore\Users\Fields\Settings;

/**
 * Class Panel_Controller
 *
 * @package Frontend_Panel
 */
class Panel_Controller
{

    /**
     * @var Panel_Settings
     */
    private $panel_settings;

    /**
     * @var Property_Manager
     */
    private $property_manager;

    /**
     * @var Submit_Property_Settings
     */
    private $submit_property_settings;

    /**
     * @var Agents_Manager
     */
    private $agents_manager;

    private $payments;

    protected $notifications;

    /**
     * Panel_Controller constructor.
     */
    public function __construct()
    {
        add_action('wp_ajax_nopriv_myhome_user_panel_login', array($this, 'login'));
        add_action('wp_ajax_nopriv_myhome_user_panel_reset_password', array($this, 'reset_password'));
        add_action('admin_post_nopriv_myhome_user_panel_reset_password_link', array($this, 'reset_password_link'));
        add_action('wp_ajax_myhome_user_panel_logout', array($this, 'logout'));
        add_action('wp_ajax_nopriv_myhome_user_send_activation_link', array($this, 'send_activation_link'));

        add_action('admin_post_myhome_user_properties', array($this, 'user_properties'));

        if ( ! empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-registration'])) {
            add_action('wp_ajax_nopriv_myhome_user_panel_register', array($this, 'register'));
        }

        if (is_admin() && current_user_can('manage_options')) {
            $this->panel_settings           = new Panel_Settings();
            $this->submit_property_settings = new Submit_Property_Settings();
        }

        $this->property_manager = new Property_Manager();
        $this->agents_manager   = new Agents_Manager();
        $this->notifications    = new Panel_Notifications();

        if (function_exists('wc_get_products')) {
            $this->payments = new Payments();
        }
    }

    /**
     * @return bool
     */
    private function verify_captcha()
    {
        $secret_key = \MyHomeCore\My_Home_Core()->settings->get('agent_captcha_secret-key');
        if (empty(trim($secret_key))) {
            return true;
        }

        $response      = wp_remote_post(
            'https://www.google.com/recaptcha/api/siteverify', array(
                'body' => array(
                    'secret'   => $secret_key,
                    'response' => $_POST['captcha'],
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                )
            )
        );
        $response_body = json_decode($response['body']);

        return ! empty($response_body->success) && $response_body->success;
    }

    public function login()
    {
        check_ajax_referer('myhome_user_panel');

        if ( ! empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-captcha']) && ! $this->verify_captcha()) {
            echo json_encode(
                array(
                    'success' => false,
                    'title'   => esc_html__('Authentication', 'myhome-core'),
                    'text'    => esc_html__('Captcha verification failed', 'myhome-core')
                )
            );
            wp_die();
        }

        $login = $_POST['credentials']['login'];
        $user  = User::get_user($login);
        if ( ! $user) {
            echo json_encode(
                array(
                    'success' => false,
                    'title'   => esc_html__('Authentication', 'myhome-core'),
                    'text'    => esc_html__('Wrong username or password', 'myhome-core'),
                )
            );
            wp_die();
        }

        if ( ! $user->is_confirmed()) {
            echo json_encode(
                array(
                    'success'                 => false,
                    'title'                   => esc_html__('Authentication', 'myhome-core'),
                    'text'                    => esc_html__('Account isn\'t active. Check your mailbox for activation link.',
                        'myhome-core'),
                    'request_activation_link' => true
                )
            );
            wp_die();
        }

        $login_data = array(
            'user_login'    => $_POST['credentials']['login'],
            'user_password' => $_POST['credentials']['password'],
            'remember'      => ! empty($_POST['rememberMe'])
        );

        $wp_user = wp_signon($login_data);
        if (is_wp_error($wp_user)) {
            echo json_encode(
                array(
                    'success' => false,
                    'title'   => esc_html__('Authentication', 'myhome-core'),
                    'text'    => esc_html__('Wrong username or password', 'myhome-core')
                )
            );

            wp_die();
        }
        wp_set_current_user($wp_user->ID);

        $this->check_draft($wp_user->ID);

        echo json_encode(
            array(
                'success' => true,
                'title'   => esc_html__('Login successful', 'myhome-core'),
                'text'    => esc_html__(sprintf('Hello %s', $user->get_name()), 'myhome-core'),
                'user'    => $user->get_data(),
                'nonce'   => wp_create_nonce('myhome_user_panel_'.$user->get_ID())
            )
        );
        wp_die();
    }

    public function logout()
    {
        wp_logout();
        wp_die();
    }

    public function register()
    {
        check_ajax_referer('myhome_user_panel');

        if ( ! empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-captcha']) && ! $this->verify_captcha()) {
            echo json_encode(
                array(
                    'success' => false,
                    'title'   => esc_html__('Authentication', 'myhome-core'),
                    'message' => esc_html__('Captcha verification failed', 'myhome-core')
                )
            );
            wp_die();
        }

        if ( ! isset($_POST['user'])) {
            echo json_encode(
                array(
                    'success' => false,
                    'message' => esc_html__('Required data is missing', 'myhome-core'),
                )
            );
            wp_die();
        }

        if (empty($_POST['user']['login']) || empty($_POST['user']['password']) || empty($_POST['user']['password'])) {
            echo json_encode(
                array(
                    'success' => false,
                    'message' => esc_html__('Required data is missing', 'myhome-core'),
                )
            );
            wp_die();
        }

        if (is_multisite()) {
            $user_login = str_replace(' ', '', trim(mb_strtolower($_POST['user']['login'], 'UTF-8')));
        } else {
            $user_login = $_POST['user']['login'];
        }
        $user_email    = $_POST['user']['email'];
        $user_password = $_POST['user']['password'];
        $results       = register_new_user($user_login, $user_email);

        // check errors
        if (is_wp_error($results)) {
            echo json_encode(
                array(
                    'success' => false,
                    'message' => $results->get_error_message(),
                )
            );

            wp_die();
        }

        $initial_role = \MyHomeCore\My_Home_Core()->settings->get('agent-initial_role');
        if (empty($initial_role)) {
            $initial_role = 'agent';
        }

        if (isset($_POST['user']['account_type'])) {
            $account_type = $_POST['user']['account_type'];
        } else {
            $account_type = '';
        }
        $set_type = \MyHomeCore\My_Home_Core()->settings->get('agent-account_type');
        if ( ! empty($set_type) && ! empty($account_type)) {
            $initial_role  = $account_type;
            $account_types = Agents_Manager::get_account_types();
            if ( ! isset($account_types[$initial_role])) {
                $initial_role = \MyHomeCore\My_Home_Core()->settings->get('agent-initial_role');
                if (empty($initial_role)) {
                    $initial_role = 'agent';
                }
            }
        }

        $wp_user = new \WP_User($results);

        wp_update_user(array('ID' => $wp_user->ID, 'role' => $initial_role));

        wp_set_password($user_password, $results);

        do_action('myhome_agent_created', $wp_user->ID);

        $activation_link = ! empty(\MyHomeCore\My_Home_Core()->settings->props['mh-agent-email_confirmation']);
        if ( ! $activation_link) {
            wp_set_auth_cookie($wp_user->ID);
            $this->check_draft($wp_user->ID);
        }

        foreach (Settings::get_fields_for_registration() as $field) {
            if (isset($_POST['user'][$field['slug']])) {
                update_user_meta($wp_user->ID, 'agent_'.$field['slug'], $_POST['user'][$field['slug']]);
            }
        }

        $user = new User($wp_user);
        echo json_encode(
            array(
                'success'       => true,
                'activate_link' => $activation_link,
                'user'          => $activation_link ? false : $user->get_data()
            )
        );

        wp_die();
    }

    private function check_draft($user_id)
    {
        if ( ! isset($_COOKIE['myhome_frontend_draft_id']) || ! isset($_COOKIE['myhome_frontend_draft'])) {
            return;
        }

        $draft_id   = intval($_COOKIE['myhome_frontend_draft_id']);
        $draft_hash = md5($_COOKIE['myhome_frontend_draft']);

        $draft = get_post($draft_id);
        if ( ! $draft instanceof \WP_Post) {
            return;
        }

        $original_draft_hash = get_post_meta($draft_id, 'myhome_frontend_draft', true);
        if (empty($original_draft_hash) || $original_draft_hash !== $draft_hash) {
            return;
        }

        setcookie('myhome_frontend_draft_id', '', time() + 60 * 60, '/');
        setcookie('myhome_frontend_draft', '', time() + 60 * 60, '/');

        update_post_meta($draft_id, 'myhome_frontend_draft', '');

        wp_update_post(array(
            'ID'          => $draft_id,
            'post_author' => $user_id
        ));
    }

    public function reset_password()
    {
        if (empty($_POST['email']) || ! is_email($_POST['email'])) {
            echo json_encode(array('success' => false));
            wp_die();
        }

        $email     = sanitize_email($_POST['email']);
        $user_data = get_user_by('email', trim(wp_unslash($email)));

        if (empty($user_data)) {
            echo json_encode(array('success' => false));
            wp_die();
        }

        if (is_multisite()) {
            $site_name = get_network()->site_name;
        } else {
            $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = wp_generate_password(20, false);

        if (empty($wp_hasher)) {
            require_once ABSPATH.WPINC.'/class-phpass.php';
            $wp_hasher = new \PasswordHash(8, true);
        }
        $hashed = time().':'.$wp_hasher->HashPassword($key);

        update_user_meta($user_data->ID, 'myhome_reset_password_key', $hashed);

        $title   = sprintf(esc_html__('[%s] Password Reset', 'myhome-core'), $site_name);
        $message =
            esc_html__('Someone has requested a password reset for the following account:', 'myhome-core')."<br>";
        $message .= sprintf(__('Username: %s'), $user_login)."<br><br>";
        $message .= esc_html__('If this was a mistake, just ignore this email and nothing will happen.', 'myhome-core')
                    ."<br><br>";
        $message .= esc_html__('To reset your password, visit the following link:', 'myhome-core')." ";
        $message .= '<a href="'.admin_url("admin-post.php?action=myhome_user_panel_reset_password_link&key=$key&id="
                                          .rawurlencode($user_data->ID)).'">'
                    .admin_url("admin-post.php?action=myhome_user_panel_reset_password_link&key=$key&id="
                               .rawurlencode($user_data->ID)).'</a>'."<br><br>";

        $message = str_replace(PHP_EOL, '<br />', $message);

        if ($message && ! wp_mail($user_email, wp_specialchars_decode($title), $message, "Content-type: text/html")) {
            echo json_encode(array('success' => false));
            wp_die();
        }

        echo json_encode(array('success' => true));

        wp_die();
    }

    public function reset_password_link()
    {
        if ( ! isset($_GET['key']) || empty($_GET['key']) || ! isset($_GET['id']) || empty($_GET['id'])) {
            wp_redirect(site_url());
            die();
        }

        $key     = $_GET['key'];
        $user_id = intval($_GET['id']);

        $user = get_user_by('id', $user_id);
        if ( ! $user instanceof \WP_User) {
            wp_redirect(site_url());
            die();
        }

        $hashed = get_user_meta($user_id, 'myhome_reset_password_key', true);

        if (empty($hashed)) {
            wp_redirect(site_url());
            die();
        }

        $temp = explode(':', $hashed);
        $time = $temp[0];

        $panel_id = \MyHomeCore\My_Home_Core()->settings->get('agent-panel_page');
        if ( ! empty($panel_id)) {
            $panel_url = get_the_permalink($panel_id);
        } else {
            $panel_url = \MyHomeCore\My_Home_Core()->settings->get('agent-panel_link');
        }

        $expire_hours = intval(\MyHomeCore\My_Home_Core()->settings->get('agent_email_confirmation-expire'));
        if ( ! $expire_hours) {
            $expire_hours = 24;
        }
        $check_date = strtotime("-$expire_hours hours");
        if ($check_date > $time) {
            wp_redirect($panel_url.'#/password-reset-link-expired');
            die();
        }

        $hashed_key = $temp[1];

        if (empty($wp_hasher)) {
            require_once ABSPATH.WPINC.'/class-phpass.php';
            $wp_hasher = new \PasswordHash(8, true);
        }

        if ($wp_hasher->CheckPassword($key, $hashed_key)) {
            $new_password = wp_generate_password();
            wp_set_password($new_password, $user_id);
            if (is_multisite()) {
                $site_name = get_network()->site_name;
            } else {
                $site_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            }

            $title   = sprintf(esc_html__('[%s] New Password', 'myhome-core'), $site_name);
            $message = sprintf(esc_html__('New password: %s', 'myhome-core'), $new_password)."\r\n\r\n";;
            $message .= esc_html__('Remember to change it right after your login.', 'myhome-core');

            if (wp_mail($user->user_email, wp_specialchars_decode($title), $message)) {
                update_user_meta($user->ID, 'myhome_reset_password_key', '');
                wp_redirect($panel_url.'#/password-reset-new');
            } else {
                wp_redirect($panel_url.'#/password-reset-error');
            }
        } else {
            wp_redirect(site_url());
        }

        die();
    }

    public function send_activation_link()
    {
        if ( ! isset($_POST['email']) || empty($_POST['email'])) {
            wp_die();
        }

        $email   = sanitize_email($_POST['email']);
        $wp_user = get_user_by('email', $email);

        if ( ! $wp_user instanceof $wp_user) {
            echo json_encode(array(
                'success' => false,
                'message' => esc_html__('Provided email not found in our database.')
            ));
            wp_die();
        }

        $user = new \MyHomeCore\Users\User($wp_user);
        if ($user->is_confirmed()) {
            echo json_encode(array(
                'success' => false,
                'message' => esc_html__('This user is already confirmed.')
            ));
            wp_die();
        }

        if ( ! Agents_Manager::send_activation_link($user)) {
            echo json_encode(array(
                'success' => false,
                'message' => esc_html__('Email sending failed', 'myhome-core')
            ));
        }

        echo json_encode(array(
            'success' => true,
            'message' => esc_html__('Your activation email has been resent.', 'myhome-core')
        ));
        wp_die();
    }

    public function user_properties()
    {
        if (empty($_POST['pagination'])) {
            return;
        }

        $pagination      = $_POST['pagination'];
        $user            = User::get_current();
        $properties_data = $user->get_properties_data($pagination['page']);
        echo json_encode($properties_data);
    }

}