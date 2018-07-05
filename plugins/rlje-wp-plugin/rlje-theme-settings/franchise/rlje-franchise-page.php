<?php

require_once 'rlje-season-page.php';
require_once 'rlje-episode-page.php';

class RLJE_Franchise_Page {

	protected $franchise;
	protected $franchise_id;
	protected $season_id;
	protected $episode_id;

	public function __construct() {
		// add_action( 'init', array( $this, 'add_franchise_rewrite_rules' ) );
		// add_action( 'generate_rewrite_rules', array( $this, 'franchise_check_against_api' ) );
		add_action( 'template_redirect', array( $this, 'franchise_template_redirect' ) );

		// add_filter( 'generate_rewrite_rules', array( $this, 'franchise_check_against_api' ) );
		add_filter( 'body_class', array( $this, 'franchise_body_class' ) );
	}

	public function add_franchise_rewrite_rules() {
		// add_rewrite_tag( '%franchise_id%', '([^&]+)' );
	}

	public function franchise_check_against_api( $wp_rewrite_rules ) {
		var_dump($wp_rewrite_rules); exit;
		return $wp_rewrite_rules;
	}

	public function franchise_template_redirect() {
		if ( $this->is_franchise() ) {
			global $wp_query;

			// Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404  = false;
			$wp_query->is_page = true;
			set_query_var( 'franchise_id', $this->franchise_id );

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/franchise.php';
			$html = ob_get_clean();
			echo $html;

			exit();
		}
	}

	public function franchise_body_class( $classes ) {
		if ( $this->is_franchise() ) {
			$classes[] = $this->franchise_id;
			$classes[] = 'page-' . $this->franchise_id;
		}

		return $classes;
	}

	protected function get_available_franchise_list() {
		$country = ( ! empty( rljeApiWP_getCountryCode() ) ) ? rljeApiWP_getCountryCode() : 'US';
		// $response = wp_remote_get( esc_url_raw( CONTENT_BASE_URL . '/today/web/franchiselist?country=' . $country ) );
		// var_dump($response);
		// if ( is_wp_error( $response ) ) {
		// 	return array();
		// }
		// $body = wp_remote_retrieve_body( $response );
		// $json = json_decode( $body, true );
		// var_dump($json);
		$current_country_available_franchises = array(
			'US' => array(
				'docmartin'    => array(
					'name' => 'Doc Martin',
				),
				'vexed'        => array(
					'name' => 'Vexed',
				),
				'vera'         => array(
					'name' => 'Vera',
				),
				'indiandoctor' => array(
					'name' => 'Indian Doctor',
				),
			),
		);
		$available_franchise_list = ( ! empty( $current_country_available_franchises[ $country ] ) ) ? $current_country_available_franchises[ $country ] : array();

		return ( ! empty( $available_franchise_list ) ) ? $available_franchise_list : array();
	}

	protected function is_franchise() {
		global $wp_query;
		// var_dump(is_page(), is_404(), is_single());
		// var_dump($wp_query);
		// exit;
		// var_dump(get_queried_object());
		// $franchise_info = get_query_var( 'pagename' );
		$pagename = $wp_query->query['pagename'];
		// var_dump($pagename,explode( '/', $pagename ));
		list( $this->franchise_id, $this->season_id, $this->episode_id ) = explode( '/', $pagename );
		// var_dump($this->franchise_id, $this->season_id, $this->episode_id);
		// exit;
		$available_franchise_list = $this->get_available_franchise_list();

		if ( is_page() ) {
			return false;
		}

		if ( ! is_404() ) {
			return false;
		}

		if ( empty( $this->franchise_id ) ) {
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
