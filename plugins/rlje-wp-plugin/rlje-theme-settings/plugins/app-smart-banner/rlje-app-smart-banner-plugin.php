<?php

class RLJE_App_Smart_Banner {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		if ( jetpack_is_mobile() ) {
			wp_enqueue_style( 'smartbanner_css', '//cdnjs.cloudflare.com/ajax/libs/jquery.smartbanner/1.0.0/jquery.smartbanner.min.css', array( 'jquery_ui_css' ), '1.0.0' );
			wp_enqueue_script( 'jquery-smartbanner-js', '//cdnjs.cloudflare.com/ajax/libs/jquery.smartbanner/1.0.0/jquery.smartbanner.min.js', array( 'jquery', 'jquery-ui-core' ), '1.0.0', true );

			wp_enqueue_script( 'rlje-app-smart-banner', plugins_url( 'js/app-smart-banner.js', __FILE__ ), array( 'jquery-smartbanner-js' ), '1.0.0', true );

			$banner_object = apply_filters( 'rlje_app_smart_banner', [
				'title' => 'Acorn TV - The Best British TV',
				'author' => 'RLJ Entertainment, Inc.',
				'price' => '',
				'in_app_store' => '',
				'in_google_play' => '',
				'icon' => plugins_url( 'img/atv-app-mobile.png', __FILE__ ),
				'button' => 'OPEN',
			] );
			wp_localize_script( 'rlje-app-smart-banner', 'rlje_app_smart_banner', $banner_object );
		}
	}
}

$rlje_app_smart_banner = new RLJE_App_Smart_Banner();
