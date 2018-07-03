<?php

class RLJE_UMC_Theme {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'atv_add_img_and_href', array( $this, 'umc_add_img_and_href' ) );
	}

	public function enqueue_scripts( $hook ) {
		$css_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' ) );
		wp_enqueue_style( 'rlje-umc-theme', plugins_url( 'css/style.css', __FILE__ ), array( 'main_style_css' ), $css_ver );
	}

	public function umc_add_img_and_href( $item ) {
		if ( ! isset( $item->href ) ) {
			$id         = ( isset( $item->id ) ) ? $item->id : $item->franchiseID;
			$item->href = $id;
		}

		if ( ! isset( $item->img ) ) {
			// $img = ( isset( $item->image ) ) ? $item->image : $item->href . '_avatar';
			$img       = ( isset( $item->image ) ) ? $item->image : $item->image_h;
			$item->img = rljeApiWP_getImageUrlFromServices( $img );
		}

		return $item;
	}
}

$rlje_umc_theme = new RLJE_UMC_Theme();
