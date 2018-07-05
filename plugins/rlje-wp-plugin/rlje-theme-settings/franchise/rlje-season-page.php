<?php

class RLJE_Season_Page extends RLJE_Franchise_Page {

	public function __construct() {
		add_action( 'template_redirect', array( $this, 'season_template_redirect' ) );

		add_filter( 'body_class', array( $this, 'season_body_class' ) );
	}

	public function season_template_redirect() {
		if ( ! $this->is_franchise() ) {
			return false;
		}

		if ( ! $this->is_season() ) {
			return false;
		}

		global $wp_query;

		// Prevent internal 404 on custome search page because of template_redirect hook.
		$wp_query->is_404  = false;
		$wp_query->is_page = true;
		set_query_var( 'season_name', $this->season_id );

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/season.php';
		$html = ob_get_clean();
		echo $html;

		exit();
	}

	public function season_body_class( $classes ) {
		if ( $this->is_franchise() ) {
			$classes[] = $this->season_id;
			$classes[] = 'page-' . $this->season_id;
		}

		return $classes;
	}

	protected function is_season() {
		if ( ! $this->is_franchise() ) {
			return false;
		}

		if ( empty( $this->season_id ) ) {
			return false;
		}

		$this->get_current_franchise();

		return true;
	}

	protected function get_current_franchise() {
		$this->franchise = rljeApiWP_getFranchiseById( $this->franchise_id );
	}
}

$rlje_season_page = new RLJE_Season_Page();
