<?php

class RLJE_Browse_Page {

	protected $nonce = 'atv#contentPage@token_nonce';

	public function __construct() {
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_paginate', array( $this, 'ajax_carousel_pagination' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

		// add_filter( 'query_vars', array( $this, 'add_browse_query_vars' ) );
		add_filter( 'body_class', array( $this, 'browse_body_class' ) );
	}

	public function add_browse_rewrite_rules() {
		add_rewrite_rule( 'browse([^/]+)/?', 'index.php?pagename=browse', 'top' );
		// add_rewrite_rule( '^browse/([^/]+)/?', 'index.php?pagename=browse&browse_type=$matches[1]', 'top' );
		// add_rewrite_tag( '%browse_type%', '([^&]+)' );

		// We are using `section` query_var set in function.php for now.
		add_rewrite_rule( 'browse/([^/]+)/?', 'index.php?pagename=browse&section=$matches[1]', 'top' );
		// add_rewrite_tag( '%section%', '([^&]+)' ); // What we want to use in the future.
	}

	public function enqueue_scripts() {
		$pagename = get_query_var( 'pagename' );
		if ( 'browse' !== $pagename ) {
			return;
		}

		$orderby_js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/orderby.js' ) );
		$pagination_js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/orderby.js' ) );

		wp_enqueue_script( 'browse-orderby-js', plugins_url( 'js/orderby.js', __FILE__ ), array( 'jquery' ), $js_ver, true );
		// Special js hook to update carousel pagination image url to use the right one for umc.
		wp_enqueue_script( 'rlje-carousel-pagination-js', plugins_url( '/js/carousel-pagination.js', __FILE__ ), array( 'jquery' ), $pagination_js_ver, true );

		$browse_object = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'home_url'  => home_url(),
			'image_url' => rljeApiWP_getImageUrlFromServices(''),
			'token'     => wp_create_nonce( $this->nonce ),
		);
		wp_localize_script( 'rlje-carousel-pagination-js', 'carousel_pagination_object', $browse_object );
	}

	public function ajax_carousel_pagination() {
		if ( ! wp_verify_nonce( $_POST['token'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		$content = ( ! empty( $_POST['content'] ) ) ? $_POST['content'] : null;
		$page = ( ! empty( $_POST['page'] ) ) ? $_POST['page'] : null;

		$data = rljeApiWP_getContentPageItems( $content, $page );
		wp_send_json_success( $data );
	}

	public function browse_template_redirect() {
		$pagename = get_query_var( 'pagename' );
		if ( 'browse' === $pagename ) {
			global $wp_query;

			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404     = false;
			$wp_query->is_page    = true;
			// $wp_query->is_archive = true;

			status_header( 200 );

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
