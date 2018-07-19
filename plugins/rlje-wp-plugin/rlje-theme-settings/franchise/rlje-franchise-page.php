<?php

require_once 'rlje-season-page.php';
require_once 'rlje-episode-page.php';
require_once 'rlje-trailer-page.php';

class RLJE_Franchise_Page {

	protected $franchise;
	protected $franchise_id;
	protected $season_id;
	protected $episode_id;
	protected $nonce = 'rlje-franchise-token-nonce';

	public function __construct() {
		add_action( 'wp', array( $this, 'get_pagename' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'template_redirect', array( $this, 'franchise_template_redirect' ), 20 );
		add_action( 'wp_ajax_add', array( $this, 'add_to_watchlist' ) );
		add_action( 'wp_ajax_remove', array( $this, 'remove_from_watchlist' ) );

		add_filter( 'body_class', array( $this, 'franchise_body_class' ) );
	}

	public function get_pagename() {
		global $wp_query;

		$pagename = $wp_query->query['pagename'];
		// var_dump($pagename,explode( '/', $pagename ));
		list( $this->franchise_id, $this->season_id, $this->episode_id ) = explode( '/', $pagename );
		// var_dump($this->franchise_id, $this->season_id, $this->episode_id);
		$this->franchise = ( ! empty( $this->franchise_id ) ) ? rljeApiWP_getFranchiseById( $this->franchise_id ) : false;
	}

	public function enqueue_scripts() {
		if ( ! $this->is_current_franchise() ) {
			return;
		}

		$js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/franchise.js' ) );
		wp_enqueue_script( 'rlje-franchise', plugins_url( 'js/franchise.js', __FILE__ ), array( 'main-js' ), $js_ver, true );
		$franchise_object = [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'franchise_id' => $this->franchise_id,
			'nonce' => wp_create_nonce( $this->nonce ),
		];
		wp_localize_script( 'rlje-franchise', 'franchise_object', $franchise_object );
	}

	public function franchise_template_redirect() {
		if ( $this->is_franchise() ) {
			global $wp_query;

			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404  = false;
			$wp_query->is_page = true;
			status_header( 200 );
			set_query_var( 'franchise_id', $this->franchise_id );

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/franchise.php';
			$html = ob_get_clean();
			echo $html;

			exit();
		}
	}

	public function add_to_watchlist() {
		if ( ! wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		if ( ! isset( $_COOKIE['ATVSessionCookie'] ) || ! rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
			die( 'Action Not Allow!' );
		}

		$franchise_id = ( ! empty( $_POST['franchise_id'] ) ) ? $_POST['franchise_id'] : '';
		if ( empty( $franchise_id ) ) {
			wp_send_json_error( array( 'message' => 'Please provide franchise ID.' ) );
		}

		$data = [
			'message' => rljeApiWP_addToWatchlist( $franchise_id, $_COOKIE['ATVSessionCookie'] ),
		];
		wp_send_json_success( $data );
	}

	public function remove_from_watchlist() {
		if ( ! wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		if ( ! isset( $_COOKIE['ATVSessionCookie'] ) || ! rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
			die( 'Action Not Allow!' );
		}

		$franchise_id = ( ! empty( $_POST['franchise_id'] ) ) ? $_POST['franchise_id'] : '';
		if ( empty( $franchise_id ) ) {
			wp_send_json_error( array( 'message' => 'Please provide franchise ID.' ) );
		}

		$data = [
			'message' => rljeApiWP_removeFromWatchlist( $franchise_id, $_COOKIE['ATVSessionCookie'] ),
		];
		wp_send_json_success( $data );
	}

	public function franchise_body_class( $classes ) {
		if ( $this->is_current_franchise() ) {
			$classes[] = $this->franchise_id;
			$classes[] = 'page-' . $this->franchise_id;
		}

		return $classes;
	}

	protected function get_available_franchise_list() {
		$country = ( ! empty( rljeApiWP_getCountryCode() ) ) ? rljeApiWP_getCountryCode() : 'US';
		$response = wp_remote_get( esc_url_raw( CONTENT_BASE_URL . '/today/web/franchiselist?country=' . $country ) );

		if ( is_wp_error( $response ) ) {
			return array();
		}
		$body = wp_remote_retrieve_body( $response );
		$current_country_available_franchises = json_decode( $body, true );
		// var_dump($current_country_available_franchises);

		$franchises = array();
		if ( empty( $current_country_available_franchises[ $country ] ) ) {
			return array();
		}

		$available_franchises = $current_country_available_franchises[ $country ];
		foreach ( $available_franchises as $franchise_id => $franchise_info ) {
			$franchise_name = $franchise_info['name'];
			$franchises[ $country ][ $franchise_id ] = $franchise_name;
		}

		$available_franchise_list = ( ! empty( $franchises[ $country ] ) ) ? $franchises[ $country ] : array();

		return ( ! empty( $available_franchise_list ) ) ? $available_franchise_list : array();
	}

	protected function is_franchise() {
		if ( is_page() ) {
			return false;
		}

		if ( ! is_404() ) {
			return false;
		}

		if ( ! $this->is_current_franchise() ) {
			return false;
		}

		return true;
	}

	protected function is_current_franchise() {
		if ( empty( $this->franchise_id ) ) {
			return false;
		}

		$available_franchise_list = $this->get_available_franchise_list();
		if ( empty( $available_franchise_list ) ) {
			// We should return 404 for not supported country
			return false;
		}

		if ( empty( $available_franchise_list[ $this->franchise_id ] ) ) {
			// We should return not available for your country template
			return false;
		}

		return true;
	}
}

$rlje_franchise_page = new RLJE_Franchise_Page();
