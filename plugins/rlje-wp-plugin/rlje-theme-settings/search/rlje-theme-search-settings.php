<?php

class RLJE_Theme_Search_Settings {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'add_search_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'search_template_redirect' ) );

		add_filter( 'get_search_form', array( $this, 'get_new_search_form' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'rlje-search', plugins_url( 'search/css/style.css', __DIR__ ), array( 'main_style_css' ), '1.0.0' );
		wp_enqueue_script( 'rlje-search', plugins_url( 'search/js/script.js', __DIR__ ), array( 'jquery' ), '1.0.0', true );
	}

	public function add_search_rewrite_rules() {
		add_rewrite_rule( '^search/([^/]+)/?', 'index.php?pagename=search&s=$matches[1]', 'top' );
	}

	public function search_template_redirect() {
		// First, redirect from ?s=text to /search/text.
		if ( is_search() and false === strpos( $_SERVER['REQUEST_URI'], '/search/' ) ) {
			wp_redirect( trailingslashit( get_bloginfo( 'home' ) . '/search/' . str_replace( ' ', '+', str_replace( '%20', '+', get_query_var( 's' ) ) ) ) );
			exit();
		}

		$pagename     = get_query_var( 'pagename' );
		$search_query = get_query_var( 's' );

		// Then, using new search rewrite rule to load new search template.
		if ( 'search' === $pagename || ! empty( $search_query ) ) {
			global $wp_query;

			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_search = true;
			$wp_query->is_404    = false;
			status_header( 200 );

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/search.php';
			$html = ob_get_clean();
			echo $html;

			exit();
		}
	}

	public function get_new_search_form( $form ) {
		ob_start();
		?>
		<form id="searchform" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
			<input id="search-button" type="submit" class="search-submit" value="">
			<input id="search-input" type="search" class="search-field" value="<?php echo get_search_query(); ?>" name="s">
		</form>
		<?php
		$new_form = ob_get_clean();

		return $new_form;
	}
}

$rlje_theme_search_settings = new RLJE_Theme_Search_Settings();
