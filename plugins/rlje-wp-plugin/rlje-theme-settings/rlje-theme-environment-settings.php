<?php

class RLJE_Theme_Environment_Settings extends RLJE_Theme_Settings {

	private $prefix = 'rlje_theme_environment_page';

	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_admin_page' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_submenu' ) );
	}

	public function register_admin_page() {
		register_setting( 'rlje_theme_environment_section', 'rlje_theme_environment_settings' );
	}

	public function register_admin_submenu() {
		add_submenu_page(
			'rlje-theme-settings',
			'Theme Environment Settings',
			'Theme Environment',
			'manage_sites',
			'admin.php?page=rlje-theme-environment-settings',
			array( $this, 'add_submenu_page' )
		);
	}

	public function add_submenu_page( $args ) {
		var_dump($args);
		// echo 'RLJE Theme Environment Settings';
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h1>RLJE Theme Environment Options</h1>
			<form method="post" action="options.php">
				<?php
					// Add_settings_section callback is displayed here. For every new section we need to call settings_fields.
					settings_fields( 'rlje_theme_environment_page' );

					// all the add_settings_field callbacks is displayed here.
					do_settings_sections( 'admin.php?page=rlje-theme-environment-settings' );

					// Add the submit button to serialize the options.
					submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

$rlje_theme_environment_settings = new RLJE_Theme_Environment_Settings();
