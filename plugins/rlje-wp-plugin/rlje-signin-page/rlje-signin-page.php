<?php

class RLJE_Signin_Page {

	private $api_helper;
	private $api_app_version        = 'UMCTV.Version.2.0';
	private $api_time_refresh_cache = 90000;

	public function __construct() {
		$this->api_helper            = new RLJE_api_helper();
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'body_class', array( $this, 'browse_body_class' ) );

	}

	public function enqueue_scripts() {
		if ( in_array( get_query_var( 'pagename' ), [ 'signin', 'forgotpassword' ] ) ) {
			wp_enqueue_style( 'signin-index', plugins_url( 'css/style.css', __FILE__ ) );
		}
	}

	public function add_browse_rewrite_rules() {
		add_rewrite_rule( '^signin([^/]+)/?', 'index.php?pagename=signin', 'top' );
		add_rewrite_rule( '^forgotpassword([^/]+)/?', 'index.php?pagename=forgot-password', 'top' );
	}

	private function cacheUserProfile( $user_profile ) {
		$session_id = $user_profile['Session']['SessionID'];
		// Cache user status
		// TODO: Maybe remove since we cache the whole user profile?
		wp_cache_set( 'userStatus_' . md5( $session_id ), 'active', 'userStatus', $this->api_time_refresh_cache );
		// Ask Transient to cache user profile
		set_transient( 'atv_userProfile_' . md5( $session_id ), $user_profile, $this->api_time_refresh_cache );
	}

	// this function authenticates the user
	private function loginUser( $email_address, $password ) {
		$request_body = [
			'App'         => [
				'AppVersion' => $this->api_app_version,
			],
			'Credentials' => [
				'Username' => $email_address,
				'Password' => $password,
			],
			'Request'     => [
				'OperationalScenario' => 'SIGNIN',
			],
		];

		$response = $this->api_helper->hit_api( $request_body, 'initializeapp', 'POST' );
		$success  = false;
		if ( isset( $response['Membership'] ) ) {
			$success    = true;
			$session_id = $response['Session']['SessionID'];
			// Set ATVSessionCookie for the authenticated user
			setcookie( 'ATVSessionCookie', $session_id, time() + ( 2 * 7 * 24 * 60 * 60 ), '/' );
			// Ask Transients to cache user data
			$this->cacheUserProfile( $response );
		}

		return $success;
	}

	private function resetPassword( $email_address ) {
		$success      = false;
		$request_body = [
			'Customer' => [
				'Email' => $email_address,
			],
		];
		if ( ! empty( $email_address ) ) {
			$response = $this->api_helper->hit_api( $request_body, 'forgotpassword', 'POST' );
			if ( isset( $response['success'] ) && $response['success'] == true ) {
				$success = true;
			}
		}
		return $success;
	}

	public function browse_template_redirect() {
		$pagename = get_query_var( 'pagename' );
		global $wp_query;

		if ( 'signin' === $pagename ) {
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				wp_redirect( home_url(), 303 );
				exit();
			}
			if ( ! empty( $_POST ) ) {
				// User login form was submitted. Athorize them against the API.
				$email_address = $_POST['user_email'];
				$password      = $_POST['user_password'];
				if ( $this->loginUser( $email_address, $password ) ) {
					// User was authenticated by API.
					// Redirect to homepage
					wp_redirect( home_url(), 303 );
					exit();
				} else {
					$message_error = 'No account with that email address exists.';
				}
			}
			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404  = false;
			$wp_query->is_page = true;
			// $wp_query->is_archive = true;
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/signin.php';
			$html = ob_get_clean();
			echo $html;
			exit();
		}
		if ( 'forgotpassword' === $pagename ) {
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				wp_redirect( home_url(), 303 );
			}
			if ( ! empty( $_POST ) ) {
				// Hook into API to reset user password
				$email_address = $_POST['user_email'];
				if ( $this->resetPassword( $email_address ) ) {
					$password_reset_failed = false;
				} else {
					$password_reset_failed = true;
				}
			}
			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404  = false;
			$wp_query->is_page = true;
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/forgot_password.php';
			$html = ob_get_clean();
			echo $html;
			exit();
		}
	}

	public function browse_body_class( $classes ) {
		$pagename = get_query_var( 'pagename' );
		if ( 'signin' === $pagename ) {
			// $classes[] = $pagename;
			$classes[] = 'page-' . $pagename;
		}

		return $classes;
	}

}

$rlje_signin_page = new RLJE_Signin_Page();
