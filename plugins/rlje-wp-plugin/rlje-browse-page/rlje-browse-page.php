<?php

class RLJE_Browse_Page {

	public function __construct() {
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

		// add_filter( 'query_vars', array( $this, 'add_browse_query_vars' ) );
		add_filter( 'body_class', array( $this, 'browse_body_class' ) );
	}

	public function add_browse_rewrite_rules() {
		add_rewrite_rule( '^browse([^/]+)/?', 'index.php?pagename=browse', 'top' );
		// add_rewrite_rule( '^browse/([^/]+)/?', 'index.php?pagename=browse&browse_type=$matches[1]', 'top' );
		// add_rewrite_tag( '%browse_type%', '([^&]+)' );

		// We are using `section` query_var set in function.php for now.
		add_rewrite_rule( '^browse/([^/]+)/?', 'index.php?pagename=browse&section=$matches[1]', 'top' );
		// add_rewrite_tag( '%section%', '([^&]+)' ); // What we want to use in the future.
	}

	public function browse_template_redirect() {
		$pagename = get_query_var( 'pagename' );
		if ( 'browse' === $pagename ) {
			global $wp_query;

			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404     = false;
			$wp_query->is_page    = true;
			// $wp_query->is_archive = true;

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/browse.php';
			$html = ob_get_clean();
			echo $html;
			// $browse_type = get_query_var( 'browse_type' );
			exit();
		}
	}

	public function browse_body_class( $classes ) {
		$pagename = get_query_var( 'pagename' );
		if ( 'browse' === $pagename ) {
			// $classes[] = $pagename;
			$classes[] = 'page-' . $pagename;

			$section = get_query_var( 'section' );
			if ( ! empty( $section ) ) {
				$classes[] = $pagename . '-' . $section;
				$classes[] = 'page-' . $section;
				// $classes[] = $section;
			}
		}

		return $classes;
	}
}

$rlje_browse_page = new RLJE_Browse_Page();
