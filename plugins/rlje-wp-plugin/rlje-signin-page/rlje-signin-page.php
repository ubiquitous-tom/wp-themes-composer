<?php

require_once("includes/helpers.php");
class RLJE_Signin_Page {

	public function __construct() {
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
		add_filter( 'body_class', array( $this, 'browse_body_class' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'signin-index', plugins_url( 'css/style.css', __FILE__ ));
	}

	public function add_browse_rewrite_rules() {
		add_rewrite_rule( '^signin([^/]+)/?', 'index.php?pagename=signin', 'top' );
		add_rewrite_rule( '^forgotpassword([^/]+)/?', 'index.php?pagename=forgot-password', 'top' );
	}

	public function browse_template_redirect() {
		$pagename = get_query_var( 'pagename' );
		global $wp_query;

		if ( 'signin' === $pagename ) {
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				wp_redirect( home_url(), 303 );
			}
			if(!empty($_POST)) {
				// User login form was submitted. Athorize them against the API.
				$email_address = $_POST['user_email'];
				$password = $_POST['user_password'];
				if(loginUser($email_address, $password)) {
					// User was authenticated by API.
					// Redirect to homepage
					wp_redirect( home_url(), 303 );
				} else {
					$message = "No account with that email address exists.";
				}
			}
			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404     = false;
			$wp_query->is_page    = true;
			// $wp_query->is_archive = true;
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/signin.php';
			$html = ob_get_clean();
			echo $html;
			exit();
		}
		if ( 'forgotpassword'  === $pagename ) {
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				wp_redirect( home_url(), 303 );
			}
			if(!empty($_POST)) {
				// Hook into API to reset user password
				$email_address = $_POST['user_email'];
				if(resetPassword($email_address)) {
					$password_reset_failed = false;
				} else {
					$password_reset_failed = true;
				}
			}
			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404     = false;
			$wp_query->is_page    = true;
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

	public function reset_user_password() {
	}
}

$rlje_signin_page = new RLJE_Signin_Page();
