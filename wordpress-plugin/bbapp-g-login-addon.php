<?php

/**
 * Plugin Name: BB App Google Login Addon
 * Plugin URI:  https://github.com/Rajinsharwar/bbapp-google-login-addon
 * Description: A BuddyBoss App addon for adding Google Login functionality.
 * Author:      Rajin Sharwar
 * Author URI:  https://linkedin.com/in/rajinsharwar
 * Version:     1.0.0
 * Text Domain: bbapp-g-login-addon
 * Domain Path: /languages/
 * License:     GPLv3 or later (license.txt)
 */

use BP_Signup;
use WP_Error as WP_Error;
use BuddyBossApp\Auth\Jwt;
use WP_REST_Controller as WP_REST_Controller;
use WP_REST_Request as WP_REST_Request;

class G_Login_Rest_Api extends WP_REST_Controller
{

   /**
    * Construct method start.
    */
   public function __construct()
   {
      $this->setup_hooks();
   }

   /**
    * Init the REST API action.
    *
    */
   protected function setup_hooks()
   {

      /**
       * Action
       */
      add_action('rest_api_init', [$this, 'register_bbapp_glogin_endpoint']);
   }

   /**
    * Register g-login route.
    *
    * @return void
    */
   public function register_bbapp_glogin_endpoint()
   {
      add_filter('bp_core_signup_send_activation_key', [$this, 'activation_of_user_registered_with_google'], 10, 5);

      register_rest_route(
         'bbapp-g-login-addon/v1',
         '/' . 'g-login',
         [
            [
               'methods' => 'POST',
               'callback' => [$this, 'bbapp_glogin_get_logins'],
            ],
         ]
      );
   }

   /**
    * Retrieve Google signups.
    *
    */
   public function bbapp_glogin_get_logins(WP_REST_Request $request)
   {
      global $wpdb;

      $username = str_replace(' ', '_', $request->get_param('username'));
      $password = $request->get_param('password');
      $email = $request->get_param('email');
      $request = apply_filters('bbapp_auth_rest_user_registration_inputs', $request);

      /**
       * Validate Incoming Inputs.
       */
      if (empty($username)) {
         return new Wp_Error('rest_bbapp_register_username_req', __('A valid username param is required.'), array('status' => 500));
      }
      if (!$request->get_param('email') || $request->get_param('email') == "") {
         return new Wp_Error('rest_bbapp_register_email_req', __('A valid email param is required.'), array('status' => 500));
      }

      if (!email_exists($request->get_param('email'))) {
         /**
          * Prepare the User Data for Registration.
          */
         $data = array();

         if ($request->get_param('first_name')) {
            $data['first_name'] = $request->get_param('first_name');
         }
         if ($request->get_param("last_name")) {
            $data['last_name'] = $request->get_param('last_name');
         }
         if ($request->get_param('display_name')) {
            $data['display_name'] = $request->get_param('display_name');
         }
         if ($request->get_param('description')) {
            $data['description'] = $request->get_param('description');
         }

         /**
          * If BuddyPress is Enabled than do Registration BuddyPress Way.
          * If platform plugin activate and select registration type is buddyboss_registration then user will create from here.
          */
         if (function_exists('bp_core_signup_user')) {
            $user_id = bp_core_signup_user($username, $password, $request->get_param('email'), $data);
         }
      }

      $jwt = Jwt::instance();

      $token_args = array(
         'expire_at_days' => 90,
      );

      $get_user_data = get_user_by('email', $email);
      $generate_token = $jwt->generate_jwt_base($get_user_data, $token_args);

      if (is_wp_error($generate_token)) {
         return $generate_token;
      }

      if (!$generate_token) {
         return new Wp_Error('jwt_token_error', __('Error while generating jwt token.', 'buddyboss-app'), array('status' => 500));
      }

      if (!empty($generate_token) && is_array($generate_token)) {
         $generate_token['access_token'] = $generate_token['token'];
         unset($generate_token['token']);
      }

      if (($device_token = @$request->get_param('deviceToken')) != '' && isset($generate_token['user_id']) && !empty($generate_token['user_id'])) {
         if (function_exists('bbapp_notifications')) {
            bbapp_notifications()->register_device_for_user($generate_token['user_id'], $device_token, $generate_token['token']);
         }
      }
       // // Send a reset password email
       // $reset_password_key = get_password_reset_key($get_user_data);
       // $reset_password_link = network_site_url("wp-login.php?action=rp&key=$reset_password_key&login=" . rawurlencode($username), 'login');

       // $subject = 'Reset Your Password';
       // $message = "Please click on the following link to reset your password: $reset_password_link";

       // wp_mail($get_user_data->user_email, $subject, $message);

         $unsubscribe_args = array(
            'user_id'           => (int) $user_id,
            'notification_type' => 'settings-password-changed',
         );

         $args = array(
            'tokens' => array(
               'reset.url'   => esc_url( wp_lostpassword_url() ),
               'unsubscribe' => esc_url( bp_email_get_unsubscribe_link( $unsubscribe_args ) ),
            ),
         );

         // Send notification email.
         bp_send_email( 'settings-password-changed', (int) $user_id, $args );

      return rest_ensure_response($generate_token);
   }

   public function activation_of_user_registered_with_google($return_true = true, $user_id, $user_email, $activation_key, $usermeta)
   {
      $activated_user = bp_core_activate_signup($activation_key);
      return false;
   }
}

$google_login_rest = new G_Login_Rest_Api();