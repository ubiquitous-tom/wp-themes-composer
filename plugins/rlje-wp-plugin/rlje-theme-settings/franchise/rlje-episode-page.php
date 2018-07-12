<?php

class RLJE_Episode_Page {

	protected $episodes;
	protected $brightcove;
	protected $nonce = 'atv#episodePlayer@token_nonce';

	public function __construct() {
		add_action( 'wp', array( $this, 'get_pagename' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_is_user_active', array( $this, 'ajax_is_user_active' ) );
		add_action( 'template_redirect', array( $this, 'episode_template_redirect' ) );

		add_filter( 'body_class', array( $this, 'episode_body_class' ) );
	}

	public function get_pagename() {
		global $wp_query;

		$pagename = $wp_query->query['pagename'];
		// var_dump($pagename,explode( '/', $pagename ));
		list( $this->franchise_id, $this->season_id, $this->episode_id ) = explode( '/', $pagename );
		// var_dump($this->franchise_id, $this->season_id, $this->episode_id);
		$this->get_current_franchise_season_episodes();
	}

	public function enqueue_scripts() {
		if ( ! $this->is_episode() ) {
			return;
		}

		$this->brightcove = get_option( 'rlje_theme_brightcove_restricted_settings' );
		if ( empty( $this->brightcove ) ) {
			return;
		}
		// UMC FREE ACCOUNT
		// $bc_account_id = '3392051363001';
		// $bc_player_id = '0066661d-8f08-4e7b-a5b4-8d48755a3057';
		// UMC PAYWALL ACCOUNT
		// $bc_account_id = '3392051362001';
		// $bc_player_id = 'e148573c-29cd-4ede-a267-a3947918ea4a';

		$bc_account_id = $this->brightcove['restricted_account_id'];
		$bc_player_id = $this->brightcove['restricted_player_id'];
		$bc_url = '//players.brightcove.net/' . $bc_account_id . '/' . $bc_player_id . '_default/index.js';
		$js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/episode.js' ) );

		wp_enqueue_script( 'brightcove', '//admin.brightcove.com/js/BrightcoveExperiences.js', array(), false, true );
		wp_enqueue_script( 'rlje-brightcove', $bc_url, array( 'jquery', 'brightcove', 'main-js' ), false, true );
		wp_enqueue_script( 'rlje-episode', plugins_url( 'js/episode.js', __FILE__ ), array( 'rlje-brightcove' ), $js_ver, true );

		wp_localize_script( 'rlje-episode', 'episode_object', array(
			// 'ajax_url' => home_url( 'ajax_atv' ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'token' => wp_create_nonce( $this->nonce )
		));
	}

	public function ajax_is_user_active() {
		if ( ! wp_verify_nonce( $_POST['token'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		$content = ( ! empty( $_POST['content'] ) ) ? $_POST['content'] : null;
		$page = ( ! empty( $_POST['page'] ) ) ? $_POST['page'] : null;

		$data = array(
			'isActive' => rljeApiWP_isUserActive( $_COOKIE["ATVSessionCookie"] ),
		);
		wp_send_json_success( $data );
	}

	public function episode_template_redirect() {
		if ( ! $this->is_episode() ) {
			return false;
		}

		global $wp_query;

		// Prevent internal 404 on custome search page because of template_redirect hook.
		$wp_query->is_404  = false;
		$wp_query->is_page = true;
		set_query_var( 'franchise_id', $this->franchise_id );
		set_query_var( 'season_name', $this->season_id );
		set_query_var( 'episode_name', $this->episode_id );

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/episode.php';
		$html = ob_get_clean();
		echo $html;

		exit();
	}

	public function episode_body_class( $classes ) {
		if ( $this->episodes ) {
			$classes[] = $this->episode_id;
			$classes[] = 'page-' . $this->episode_id;
		}

		return $classes;
	}

	protected function is_episode() {
		if ( empty( $this->episode_id ) ) {
			return false;
		}

		if ( empty( $this->episodes ) ) {
			return false;
		}

		return true;
	}

	protected function get_current_franchise_season_episodes() {
		if ( empty( $this->episode_id ) ) {
			$this->episodes = false;
		} else {
			$this->episodes = rljeApiWP_getCurrentEpisode( $this->franchise_id, $this->season_id, $this->episode_id );
		}
	}
}

$rlje_episode_page = new RLJE_Episode_Page();
