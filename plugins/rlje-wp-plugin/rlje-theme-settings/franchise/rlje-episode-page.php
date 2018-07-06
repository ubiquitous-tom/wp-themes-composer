<?php

class RLJE_Episode_Page {

	protected $episodes;

	public function __construct() {
		add_action( 'wp', array( $this, 'get_pagename' ) );
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
