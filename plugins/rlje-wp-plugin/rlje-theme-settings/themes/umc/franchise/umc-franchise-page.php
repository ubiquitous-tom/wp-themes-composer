<?php

class UMC_Franchise_page {

	public function __construct() {
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// add_filter( 'rlje_franchise_page_template_path', array( $this, 'umc_franchise_page_template_path' ) );
	}

	public function enqueue_scripts() {
		$franchise_css_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/umc-franchise.css' ) );
		wp_enqueue_style( 'rlje-umc-franchise-theme', plugins_url( 'css/umc-franchise.css', __FILE__ ), array( 'rlje-umc-theme' ), $franchise_css_ver );
	}

	public function umc_franchise_page_template_path( $template_path ) {
		$template_path = plugin_dir_path( __FILE__ ) . 'templates/franchise.php';

		return $template_path;
	}
}

$umc_franchise_page = new UMC_Franchise_page();
