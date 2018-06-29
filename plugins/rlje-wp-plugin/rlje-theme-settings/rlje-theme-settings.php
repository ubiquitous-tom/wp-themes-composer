<?php

class RLJE_Theme_Settings {

	private $theme_settings = array();
	private $sailthru = array();
	private $rightsline = array();

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_init', array( $this, 'display_options' ) );
		add_action( 'admin_menu', array( $this, 'add_theme_settings_menu' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_rlje-theme-settings' === $hook ) {
			wp_enqueue_script( 'rlje-theme-setting', plugins_url( 'js/script.js', __FILE__ ) );
		}
	}

	public function add_theme_settings_menu() {
		// This needs to be updated to `manage_network` so only Super Admin can edit this.
		add_menu_page(
			'RLJE Theme Settings', // Required. Text in browser title bar when the page associated with this menu item is displayed.
			'Theme Settings', // Required. Text to be displayed in the menu.
			'manage_options', // Required. The required capability of users to access this menu item.
			'rlje-theme-settings', // Required. A unique identifier to identify this menu item.
			array( $this, 'rlje_theme_settings' ), // Optional. This callback outputs the content of the page associated with this menu item.
			'', // Optional. The URL to the menu item icon.
			100 // Optional. Position of the menu item in the menu.
		);
	}

	public function rlje_theme_settings() {
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>

			<!-- run the settings_errors() function here. -->
			<?php //settings_errors(); ?>

			<h1>RLJE Theme Options</h1>

			<?php
			// We check if the page is visited by click on the tabs or on the menu button.
			// Then we get the active tab.
			$tab = ( ! empty( $_GET['tab'] ) ) ? $_GET['tab'] : '';
			switch ( $tab ) {
				case '3rd-party-options':
					$active_tab    = '3rd-party-options';
					$active_fields = 'rlje_3rd_party_section';
					break;
				default:
					$active_tab    = 'main-options';
					$active_fields = 'rlje_theme_section';
			}
			?>

			<!-- WordPress provides the styling for tabs. -->
			<h2 class="nav-tab-wrapper">
				<!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
				<a href="?page=rlje-theme-settings&tab=main-options" class="nav-tab <?php if ( 'main-options' === $active_tab ) { echo 'nav-tab-active'; } ?> ">Theme Options</a>
				<a href="?page=rlje-theme-settings&tab=3rd-party-options" class="nav-tab <?php if ( '3rd-party-options' === $active_tab ) { echo 'nav-tab-active'; } ?>">3rd Party Options</a>
			</h2>
			<form method="post" action="options.php">
				<?php
					// Add_settings_section callback is displayed here. For every new section we need to call settings_fields.
					settings_fields( $active_fields );

					// all the add_settings_field callbacks is displayed here.
					do_settings_sections( 'rlje-theme-settings' );

					// Add the submit button to serialize the options.
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function display_options() {
		register_setting( 'rlje_theme_section', 'rlje_theme_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_sailthru_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_rightsline_settings' );
		register_setting( 'rlje_3rd_party_section', 'rlje_google_settings' );
		// Here we display the sections and options in the settings page based on the active tab.
		$tab = ( ! empty( $_GET['tab'] ) ) ? $_GET['tab'] : '';
		switch ( $tab ) {
			case '3rd-party-options':
				add_settings_section( 'rlje_google_analytics_section', 'Sailthru Options', array( $this, 'display_google_analytics_options_content' ), 'rlje-theme-settings' );
				add_settings_field( 'google_analytics', 'Google Analytics ID', array( $this, 'display_google_analytics_settings' ), 'rlje-theme-settings', 'rlje_google_analytics_section' );

				add_settings_section( 'rlje_sailthru_section', 'Sailthru Options', array( $this, 'display_sailthru_options_content' ), 'rlje-theme-settings' );
				add_settings_field( 'sailthru', 'Sailthru Customer ID', array( $this, 'display_sailthru_settings' ), 'rlje-theme-settings', 'rlje_sailthru_section' );

				add_settings_section( 'rlje_rightsline_section', 'Rightsline Options', array( $this, 'display_rightsline_options_content' ), 'rlje-theme-settings' );
				add_settings_field( 'rightsline_base_url', 'Base URL', array( $this, 'display_rightsline_base_url' ), 'rlje-theme-settings', 'rlje_rightsline_section' );
				add_settings_field( 'rightsline_auth_type', 'Auth Type', array( $this, 'display_rightsline_auth_type' ), 'rlje-theme-settings', 'rlje_rightsline_section' );
				add_settings_field( 'rightsline_auth_header', 'Auth Header', array( $this, 'display_rightsline_auth_header' ), 'rlje-theme-settings', 'rlje_rightsline_section' );
				break;

			default:
				// Section name, display name, callback to print description of section, page to which section is attached.
				add_settings_section( 'rlje_theme_section', 'Theme Options', array( $this, 'display_rlje_options_content' ), 'rlje-theme-settings' );
				// Setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
				// Last field section is optional.
				add_settings_field( 'environment_type', 'Environment Type', array( $this, 'display_environment_type' ), 'rlje-theme-settings', 'rlje_theme_section' );
				add_settings_field( 'rlje_base_url', 'RLJE Base URL', array( $this, 'display_rlje_base_url' ), 'rlje-theme-settings', 'rlje_theme_section' );
				add_settings_field( 'content_base_url', 'Content Base URL', array( $this, 'display_content_base_url' ), 'rlje-theme-settings', 'rlje_theme_section' );
		}
	}

	public function display_rlje_options_content() {
		// echo 'RLJE Theme Settings';
		$this->theme_settings = get_option( 'rlje_theme_settings' );
		var_dump($this->theme_settings);
	}

	public function display_environment_type() {
		$env_type = ( ! empty( $this->theme_settings['environment_type'] ) ) ? $this->theme_settings['environment_type'] : 'DEV';
		?>
		<select name="rlje_theme_settings[environment_type]" id="environmen-type" class="regular-text">
			<option value="DEV" <?php selected( $env_type, 'DEV' ); ?>>Development</option>
			<option value="QA" <?php selected( $env_type, 'QA' ); ?>>QA</option>
			<option value="PROD" <?php selected( $env_type, 'PROD' ); ?>>PROD</option>
		</select>
		<?php
	}

	public function display_rlje_base_url() {
		$rlje_base_url = ( ! empty( $this->theme_settings['rlje_base_url'] ) ) ? $this->theme_settings['rlje_base_url'] : 'https://dev-api.rlje.net/acorn';
		?>
		<input type="text" name="rlje_theme_settings[rlje_base_url]" id="rlje-base-url" class="regular-text" value="<?php echo esc_url( $rlje_base_url ); ?>" placeholder="RLEJ base URL">
		<p class="description">URL for the main RLJE site (https://dev-api.rlje.net/acorn) - no trailing slash</p>
		<?php
	}

	public function display_content_base_url() {
		$content_base_url = ( ! empty( $this->theme_settings['content_base_url'] ) ) ? $this->theme_settings['content_base_url'] : 'https://dev-api.rlje.net/cms/acorn';
		?>
		<input type="text" name="rlje_theme_settings[content_base_url]" id="content-base-url" class="regular-text" value="<?php echo esc_url( $content_base_url ); ?>" placeholder="RLJE base content URL">
		<p class="description">URL for the site content from RLJE API (https://dev-api.rlje.net/cms/acorn) - no trailing slash</p>
		<?php
	}

	public function display_google_analytics_options_content() {
		echo 'Google Analytics Settings';
		$this->google = get_option( 'rlje_google_settings' );
		var_dump($this->google);
	}

	public function display_google_analytics_settings() {
		$google_analytics_id = ( ! empty( $this->google['google_analytics_id'] ) ) ? $this->google['google_analytics_id'] : '';
		?>
		<input type="text" name="rlje_google_settings[google_analytics_id]" id="ga-id" class="regular-text" value="<?php echo esc_attr( $google_analytics_id ); ?>" placeholder="Google Analytics ID">
		<p class="description">Please enter your Google Analytics ID</p>
		<?php
	}

	public function display_sailthru_options_content() {
		echo 'Sailthru Settings';
		$this->sailthru = get_option( 'rlje_sailthru_settings', array() );
		var_dump($this->sailthru);
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
		var_dump($this->rightsline);
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

	public function sanitize_options( $data = null ) {
		$message = 'Data can not be empty';
		$type = 'error';

		if ( null !== $data ) {
			if ( false === get_option( 'rlje_theme_settings' ) ) {
				add_option( 'rlje_theme_settings', $data );
				$type = 'updated';
				$message = __( 'Successfully saved', 'my-text-domain' );
			} else {
				update_option( 'rlje_theme_settings', $data );
				$type = 'updated';
				$message = __( 'Successfully updated', 'my-text-domain' );
			}
		}

		add_settings_error(
			'rlje-theme-setting-notice',
			esc_attr( 'settings_updated' ),
			$message,
			$type
		);
	}
}

$rlje_theme_settings = new RLJE_Theme_Settings();

require_once 'header/rlje-header.php';
require_once 'navigations/rlje-theme-menu-settings.php';
require_once 'search/rlje-theme-search-settings.php';
