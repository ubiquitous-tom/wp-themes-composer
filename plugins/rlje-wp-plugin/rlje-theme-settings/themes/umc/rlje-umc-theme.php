<?php

class RLJE_UMC_Theme {

	protected $theme = 'umc';

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'rlje_theme_header_logo', array( $this, 'theme_header_logo' ), 11 );
		add_filter( 'atv_add_img_and_href', array( $this, 'umc_add_img_and_href' ) );
	}

	public function enqueue_scripts( $hook ) {
		wp_enqueue_style( 'google-webfont-nunito', '//fonts.googleapis.com/css?family=Nunito:400,400i,600,600i,700,700i' );

		$css_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' ) );
		wp_enqueue_style( 'rlje-umc-theme', plugins_url( 'css/style.css', __FILE__ ), array( 'main_style_css' ), $css_ver );

		$umc_carousel_pagination_js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/umc-carousel-pagination.js' ) );
		wp_enqueue_script( 'rlje-umc-carousel-pagination-js', plugins_url( 'js/umc-carousel-pagination.js', __FILE__ ), array( 'rlje-carousel-pagination-js' ), $umc_carousel_pagination_js_ver, true );
	}

	public function theme_header_logo( $logo_url ) {
		$logo_url = plugin_dir_url( __FILE__ ) . 'img/logo.png';

		return $logo_url;
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
