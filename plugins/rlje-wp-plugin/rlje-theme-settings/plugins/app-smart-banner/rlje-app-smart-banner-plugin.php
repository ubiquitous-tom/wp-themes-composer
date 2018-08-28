<?php

class RLJE_App_Smart_Banner {

	public $smart_banner;

	public function __construct() {
		add_action( 'init', array( $this, 'init_smart_banner' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'smart_app_banner_header_meta' ), 0 );
	}

	public function init_smart_banner() {
		$this->smart_banner = apply_filters( 'rlje_app_smartbanner', [
			'title' => 'Acorn TV - The Best British TV',
			'author' => 'RLJ Entertainment, Inc.',
			'price' => 'FREE',
			'price-suffix-apple' => ' - On the App Store',
			'price-suffix-google' => ' - In Google Play',
			'icon-apple' => plugins_url( 'img/atv-app-mobile.png', __FILE__ ),
			'icon-google' => plugins_url( 'img/atv-app-mobile.png', __FILE__ ),
			'button' => 'VIEW',
			'button-url-apple' => 'https://itunes.apple.com/us/app/acorn-tv-the-best-british-tv/id896014310?mt=8',
			'button-url-google' => 'https://play.google.com/store/apps/details?id=com.acorn.tv&hl=en_US',
			'enabled-platforms' => 'android,ios',
		] );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'smartbanner_css', plugins_url( 'css/smartbanner.css', __FILE__ ), array(), '1.10.0' );
		wp_enqueue_script( 'smartbanner-js', plugins_url( 'js/smartbanner.js', __FILE__ ), array(), '1.10.0', true );
	}

	public function smart_app_banner_header_meta() {
		foreach ( $this->smart_banner as $name => $content ) {
			echo '<meta name="smartbanner:' . $name . '" content="' . $content . '">' . "\n";
		}
	}
}

$rlje_app_smart_banner = new RLJE_App_Smart_Banner();
