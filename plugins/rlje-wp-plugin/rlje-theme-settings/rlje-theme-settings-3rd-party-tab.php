<?php

class RLJE_Theme_Settings_3rd_Party_Tab {

	protected $apple      = [];
	protected $google     = [];
	protected $sailthru   = [];
	protected $rightsline = [];
	protected $tealium    = [];

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'display_options' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_rlje-theme-settings' === $hook ) {
			wp_enqueue_script( 'rlje-theme-setting', plugins_url( 'settings/3rd-party-tab/js/script.js', __FILE__ ) );
		}
	}

	public function display_options() {
		register_setting( 'rlje_3rd_party_section', 'rlje_sailthru_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_rightsline_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_google_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_apple_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_tealium_settings' );

		// Here we display the sections and options in the settings page based on the active tab.
		$tab = ( ! empty( $_GET['tab'] ) ) ? $_GET['tab'] : '';
		if ( '3rd-party-options' === $tab ) {
			add_settings_section( 'rlje_apple_section', 'Apple Options', array( $this, 'display_apple_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'apple_itunes_app_id', 'Apple Itunes App', array( $this, 'display_apple_itunes_app_settings' ), 'rlje-theme-settings', 'rlje_apple_section' );

			add_settings_section( 'rlje_google_section', 'Google Options', array( $this, 'display_google_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'google_analytics', 'Google Analytics', array( $this, 'display_google_analytics_settings' ), 'rlje-theme-settings', 'rlje_google_section' );
			add_settings_field( 'google_site_verification', 'Google Site Verification', array( $this, 'display_google_site_verification_settings' ), 'rlje-theme-settings', 'rlje_google_section' );
			add_settings_field( 'google_play_app_id', 'Google Play App', array( $this, 'display_google_play_app_settings' ), 'rlje-theme-settings', 'rlje_google_section' );

			add_settings_section( 'rlje_sailthru_section', 'Sailthru Options', array( $this, 'display_sailthru_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'sailthru', 'Sailthru Customer ID', array( $this, 'display_sailthru_settings' ), 'rlje-theme-settings', 'rlje_sailthru_section' );

			add_settings_section( 'rlje_rightsline_section', 'Rightsline Options', array( $this, 'display_rightsline_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'rightsline_base_url', 'Base URL', array( $this, 'display_rightsline_base_url' ), 'rlje-theme-settings', 'rlje_rightsline_section' );
			add_settings_field( 'rightsline_auth_type', 'Auth Type', array( $this, 'display_rightsline_auth_type' ), 'rlje-theme-settings', 'rlje_rightsline_section' );
			add_settings_field( 'rightsline_auth_header', 'Auth Header', array( $this, 'display_rightsline_auth_header' ), 'rlje-theme-settings', 'rlje_rightsline_section' );

			add_settings_section( 'rlje_tealium_section', 'Tealium Options', array( $this, 'display_tealium_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'tealium_id', 'Tealium Tag', array( $this, 'display_tealium_settings' ), 'rlje-theme-settings', 'rlje_tealium_section' );
		}
	}

	public function display_apple_options_content() {
		echo 'Apple Related Settings';
		$this->apple = get_option( 'rlje_apple_settings' );
		var_dump( $this->apple );
	}

	public function display_apple_itunes_app_settings() {
		$apple_itunes_app_id = ( ! empty( $this->apple['apple_itunes_app_id'] ) ) ? $this->apple['apple_itunes_app_id'] : '';
		?>
		<input type="text" name="rlje_apple_settings[apple_itunes_app_id]" id="apple-itunes-app-id" class="regular-text" value="<?php echo esc_attr( $apple_itunes_app_id ); ?>" placeholder="Apple Itunes APP ID">
		<p class="description">Please enter your Apple Itunes APP ID. (e.g., app-id=896014310)</p>
		<?php
	}

	public function display_google_options_content() {
		echo 'Google Related Settings';
		$this->google = get_option( 'rlje_google_settings' );
		var_dump( $this->google );
	}

	public function display_google_analytics_settings() {
		$google_analytics_id = ( ! empty( $this->google['google_analytics_id'] ) ) ? $this->google['google_analytics_id'] : '';
		?>
		<input type="text" name="rlje_google_settings[google_analytics_id]" id="ga-id" class="regular-text" value="<?php echo esc_attr( $google_analytics_id ); ?>" placeholder="Google Analytics ID">
		<p class="description">Please enter your Google Analytics ID. (e.g., GTM-5C5NK6)</p>
		<?php
	}

	public function display_google_site_verification_settings() {
		$google_site_verification_id = ( ! empty( $this->google['google_site_verification_id'] ) ) ? $this->google['google_site_verification_id'] : '';
		?>
		<input type="text" name="rlje_google_settings[google_site_verification_id]" id="google-site-verification-id" class="regular-text" value="<?php echo esc_attr( $google_site_verification_id ); ?>" placeholder="Google Site Verification ID">
		<p class="description">Please enter your Google Site Verification ID. (e.g., QCrNnLN11eCtEq_RIVjUQEXRabEJewu4tPwxbjJHHj4)</p>
		<?php
	}

	public function display_google_play_app_settings() {
		$google_play_app_id = ( ! empty( $this->google['google_play_app_id'] ) ) ? $this->google['google_play_app_id'] : '';
		?>
		<input type="text" name="rlje_google_settings[google_play_app_id]" id="google-play-app-id" class="regular-text" value="<?php echo esc_attr( $google_play_app_id ); ?>" placeholder="Google Play APP ID">
		<p class="description">Please enter your Google Play APP ID. (e.g., app-id=com.acorn.tv)</p>
		<?php
	}

	public function display_sailthru_options_content() {
		echo 'Sailthru Settings';
		$this->sailthru = get_option( 'rlje_sailthru_settings', array() );
		var_dump( $this->sailthru );
	}

	public function display_sailthru_settings() {
		$sailthru_customer_id = ( ! empty( $this->sailthru['customer_id'] ) ) ? $this->sailthru['customer_id'] : '';
		?>
		<input type="text" name="rlje_sailthru_settings[customer_id]" id="sailthru-customer-id" class="regular-text" value="<?php echo esc_attr( $sailthru_customer_id ); ?>" placeholder="Sailthru Customer ID">
		<p class="description">Please enter your sailthru customer id</p>
		<?php
	}

	public function display_rightsline_options_content() {
		echo 'Rightsline Settings';
		$this->rightsline = get_option( 'rlje_rightsline_settings' );
		var_dump( $this->rightsline );
	}

	public function display_rightsline_base_url() {
		$rightsline_base_url = ( ! empty( $this->rightsline['base_url'] ) ) ? $this->rightsline['base_url'] : 'http://api.rightsline.com';
		?>
		<input type="text" name="rlje_rightsline_settings[base_url]" id="rightsline-base-url" class="regular-text" value="<?php echo esc_url( $rightsline_base_url ); ?>" placeholder="Rightsline API URL">
		<p class="description">URL for the Rightsline API (http://api.rightsline.com) - no trailing slash</p>
		<?php
	}

	public function display_rightsline_auth_type() {
		$rightsline_auth_type = ( ! empty( $this->rightsline['auth_type'] ) ) ? $this->rightsline['auth_type'] : '';
		?>
		<select name="rlje_rightsline_settings[auth_type]" id="rightsline-auth-type">
			<option value="Basic">Basic</option>
			<option value="Bearer">Bearer</option>
			<option value="AWS4-HMAC-SHA256">AWS Signature Version 4</option>
		</select>
		<p data-auth-type="Basic" class="auth-type description hidden">Basic Example - Authorization: Basic Ym9zY236Ym9zY28=</p>
		<p data-auth-type="Bearer" class="auth-type description hidden">Bearer Example - Authorization: Bearer AbCdEf123456</p>
		<p data-auth-type="AWS4-HMAC-SHA256" class="auth-type description hidden">AWS Example - Authorization: AWS4-HMAC-SHA256<br>
			Credential=AKIAIOSFODNN7EXAMPLE/20130524/us-east-1/s3/aws4_request,<br>
			SignedHeaders=host;range;x-amz-date,<br>
			Signature=fe5f80f77d5fa3beca038a248ff027d0445342fe2855ddc963176630326f1024</p>
		<?php
	}

	public function display_rightsline_auth_header() {
		$rightsline_auth_header = ( ! empty( $this->rightsline['auth_header'] ) ) ? $this->rightsline['auth_header'] : '';
		?>
		<input type="text" name="rlje_rightsline_settings[auth_header]" id="rightsline-auth-header" class="regular-text" value="<?php echo esc_attr( $rightsline_auth_header ); ?>" placeholder="Rightsline Auth Header">
		<p class="description">Please enter your Rightsline auth header</p>
		<?php
	}

	public function display_tealium_options_content() {
		echo 'Tealium Settings';
		$this->tealium = get_option( 'rlje_tealium_settings' );
		var_dump( $this->tealium );
	}

	public function display_tealium_settings() {
		$tealium_id = ( ! empty( $this->tealium['tealium_id'] ) ) ? $this->tealium['tealium_id'] : '';
		?>
		<input type="text" name="rlje_tealium_settings[tealium_id]" id="tealium-id" class="regular-text" value="<?php echo esc_attr( $tealium_id ); ?>" placeholder="Tealium ID">
		<p class="description">Please enter your Tealium ID. (e.g., 6377)</p>
		<?php
	}
}

$rlje_theme_settings_3rd_party_tab = new RLJE_Theme_Settings_3rd_Party_Tab();
