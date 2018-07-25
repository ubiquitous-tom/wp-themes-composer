<?php
class RLJE_signup_page {
	public function __construct() {
		add_action( 'init', array( $this, 'add_signup_rewrite_rule' ) );
		add_action( 'template_redirect', array( $this, 'signup_template_redirect' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		if ( in_array( get_query_var( 'pagename' ), [ 'signup' ] ) ) {
			wp_enqueue_style( 'account-main-style', plugins_url( 'css/style.css', __FILE__ ) );
		}
	}

	public function add_signup_rewrite_rule() {
		add_rewrite_rule( '^signup/?', 'index.php?pagename=signup', 'top' );
	}

	public function signup_template_redirect() {
		global $wp_query;
		$pagename = get_query_var( 'pagename' );

		if ( 'signup' === $pagename ) {
			// Prevent internal 404 on custome search page because of template_redirect hook.
			status_header( 200 );
			$wp_query->is_404  = false;
			$wp_query->is_page = true;

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/main.php';
			$html = ob_get_clean();
			echo $html;
			exit();
		}
	}
}

$rlje_signup_page = new RLJE_signup_page();
