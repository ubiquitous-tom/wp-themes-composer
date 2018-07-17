<?php

class RLJE_Theme_Settings {

	protected $theme_settings         = array();
	protected $theme_plugins_settings = array();
	protected $rlje_redis_table;

	public function __construct() {
		$this->theme_settings_include_files();

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );

		add_action( 'admin_init', array( $this, 'display_options' ) );
		add_action( 'admin_menu', array( $this, 'add_theme_settings_menu' ) );

		// CORE THEME PAGES.
		require_once 'header/rlje-header.php';
		require_once 'footer/rlje-footer.php';
		require_once 'widgets/rlje-widget.php';
		require_once 'navigations/rlje-theme-menu-settings.php';
		require_once 'search/rlje-theme-search-settings.php';
		require_once 'index/rlje-index-page.php';
		require_once 'schedule/rlje-schedule-page.php';
		require_once 'franchise/rlje-franchise-page.php';

		// CORE THEME PLUGINS.
		require_once 'plugins/country/rlje-country-plugin.php';
		require_once 'plugins/futuredate/rlje-futuredate-plugin.php';
		require_once 'plugins/videodebugger/rlje-videodebugger-plugin.php';

		// CORE THEME SETTINGS PAGES.
		require_once 'rlje-theme-settings-3rd-party-tab.php';
		require_once 'rlje-theme-environment-settings.php';
		require_once 'rlje-theme-brightcove-settings.php';
		require_once 'rlje-theme-redis-settings.php';

	}

	public function display_options() {
		$this->rlje_redis_table = new RLJE_Redis_Table();

		register_setting( 'rlje_theme_section', 'rlje_theme_settings', array( $this, 'sanitize_callback' ) );
		register_setting( 'rlje_theme_section', 'rlje_theme_plugins_settings' );

		// Here we display the sections and options in the settings page based on the active tab.
		$tab = ( ! empty( $_GET['tab'] ) ) ? $_GET['tab'] : '';
		if ( empty( $tab ) || ( 'main-options' === $tab ) ) {
			add_settings_section( 'rlje_theme_section', 'Theme Options', array( $this, 'display_rlje_theme_options_content' ), 'rlje-theme-settings' );
			add_settings_field( 'environment_type', 'Current Theme', array( $this, 'display_theme_switcher' ), 'rlje-theme-settings', 'rlje_theme_section' );

			add_settings_section( 'rlje_theme_plugins_section', 'Plugins Options', array( $this, 'display_rlje_theme_plugins_content' ), 'rlje-theme-settings' );
			add_settings_field( 'theme_plugins_front_page', 'Home Page', array( $this, 'display_theme_plugins_front_page' ), 'rlje-theme-settings', 'rlje_theme_plugins_section' );
			add_settings_field( 'theme_plugins_landing_page', 'Landing Pages', array( $this, 'display_theme_plugins_landing_page' ), 'rlje-theme-settings', 'rlje_theme_plugins_section' );
			add_settings_field( 'theme_plugins_news_and_reviews', 'News And Reviews', array( $this, 'display_theme_plugins_news_and_reviews' ), 'rlje-theme-settings', 'rlje_theme_plugins_section' );
		}
	}

	public function add_theme_settings_menu() {
		// This needs to be updated to `manage_network` so only Super Admin can edit this.
		add_menu_page(
			'RLJE Theme Settings', // Required. Text in browser title bar when the page associated with this menu item is displayed.
			'Theme Settings', // Required. Text to be displayed in the menu.
			'manage_sites', // Required. The required capability of users to access this menu item.
			'rlje-theme-settings', // Required. A unique identifier to identify this menu item.
			array( $this, 'rlje_theme_settings_page' ), // Optional. This callback outputs the content of the page associated with this menu item.
			'', // Optional. The URL to the menu item icon.
			100 // Optional. Position of the menu item in the menu.
		);
	}

	public function rlje_theme_settings_page() {
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
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
				<a href="?page=rlje-theme-settings&tab=main-options" class="nav-tab
				<?php
				if ( 'main-options' === $active_tab ) {
					echo 'nav-tab-active'; }
				?>
				">Theme Options</a>
				<a href="?page=rlje-theme-settings&tab=3rd-party-options" class="nav-tab
				<?php
				if ( '3rd-party-options' === $active_tab ) {
					echo 'nav-tab-active'; }
				?>
				">3rd Party Options</a>
			</h2>
			<?php settings_errors(); ?>
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

	public function display_rlje_theme_options_content() {
		echo 'Load all the specific theme stylesheet and plugins';
		$this->theme_settings = get_option( 'rlje_theme_settings' );
		var_dump( $this->theme_settings );
	}

	public function display_theme_switcher() {
		$current_theme = ( ! empty( $this->theme_settings['current_theme'] ) ) ? $this->theme_settings['current_theme'] : 'acorn';
		?>
		<select name="rlje_theme_settings[current_theme]" id="current-theme" class="regular-text">
			<option value="acorn" <?php selected( $current_theme, 'acorn' ); ?>>Acorn</option>
			<option value="umc" <?php selected( $current_theme, 'umc' ); ?>>UMC</option>
		</select>
		<?php
	}

	public function display_rlje_theme_plugins_content() {
		echo 'Toggle for other RLJE plugins for the theme';
		$this->theme_plugins_settings = get_option( 'rlje_theme_plugins_settings' );
		var_dump( $this->theme_plugins_settings );
	}

	public function display_theme_plugins_front_page() {
		$front_page = ( ! intval( $this->theme_plugins_settings['front_page'] ) ) ? intval( $this->theme_plugins_settings['front_page'] ) : 1;
		?>
		<input type="radio" name="rlje_theme_plugins_settings[front_page]" id="rlje-plugins-front-page-on" class="regular-text" value="1" <?php checked( $front_page, 1 ); ?>>
		<label for="rlje-plugins-front-page-on">On</label>
		<br>
		<input type="radio" name="rlje_theme_plugins_settings[front_page]" id="rlje-plugins-front-page=of" class="regular-text" value="0" <?php checked( $front_page, 0 ); ?>>
		<label for="rlje-plugins-front-page-off">Off</label>
		<p class="description">For activating Homepage Hero</p>
		<?php
	}

	public function display_theme_plugins_landing_page() {
		$landing_pages = ( ! intval( $this->theme_plugins_settings['landing_pages'] ) ) ? intval( $this->theme_plugins_settings['landing_pages'] ) : 1;
		?>
		<input type="radio" name="rlje_theme_plugins_settings[landing_pages]" id="rlje-plugins-landing-page-on" class="regular-text" value="1" <?php checked( $landing_pages, 1 ); ?>>
		<label for="rlje-plugins-landing-page-on">On</label>
		<br>
		<input type="radio" name="rlje_theme_plugins_settings[landing_pages]" id="rlje-plugins-landing-page=of" class="regular-text" value="0" <?php checked( $landing_pages, 0 ); ?>>
		<label for="rlje-plugins-landing-page-off">Off</label>
		<p class="description">For activating Franchise landing page</p>
		<?php
	}

	public function display_theme_plugins_news_and_reviews() {
		$news_and_reviews = ( ! intval( $this->theme_plugins_settings['news_and_reviews'] ) ) ? intval( $this->theme_plugins_settings['news_and_reviews'] ) : 1;
		?>
		<input type="radio" name="rlje_theme_plugins_settings[news_and_reviews]" id="rlje-plugins-news-and-review-on" class="regular-text" value="1" <?php checked( $news_and_reviews, 1 ); ?>>
		<label for="rlje-plugins-news-and-review-on">On</label>
		<br>
		<input type="radio" name="rlje_theme_plugins_settings[news_and_reviews]" id="rlje-plugins-news-and-review=of" class="regular-text" value="0" <?php checked( $news_and_reviews, 0 ); ?>>
		<label for="rlje-plugins-news-and-review-off">Off</label>
		<p class="description">For activating Homepage section</p>
		<?php
	}

	public function sanitize_options( $data = null ) {
		$message = 'Data can not be empty';
		$type    = 'error';

		if ( null !== $data ) {
			if ( false === get_option( 'rlje_theme_settings' ) ) {
				add_option( 'rlje_theme_settings', $data );
				$type    = 'updated';
				$message = __( 'Successfully saved', 'my-text-domain' );
			} else {
				update_option( 'rlje_theme_settings', $data );
				$type    = 'updated';
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

	public function plugins_loaded() {
		$this->theme_settings             = get_option( 'rlje_theme_settings' );
		$this->theme_environment_settings = get_option( 'rlje_theme_environment_settings' );
		$this->sailthru                   = get_option( 'rlje_sailthru_settings' );
		$this->rightsline                 = get_option( 'rlje_rightsline_settings' );

		// For RLJE API call.
		define( 'API_TIMEOUT_SECS', '30' );

		$env_type = ( ! empty( $this->theme_environment_settings['environment_type'] ) ) ? $this->theme_environment_settings['environment_type'] : 'DEV';
		if ( ! empty( $env_type ) ) {
			define( 'ENVIRONMENT', $env_type );
			if ( 'DEV' === $env_type ) {
				define( 'JETPACK_DEV_DEBUG', true );
			}
		}

		$rlje_base_url = ( ! empty( $this->theme_environment_settings['rlje_base_url'] ) ) ? $this->theme_environment_settings['rlje_base_url'] : 'https://dev-api.rlje.net/acorn';
		if ( ! empty( $rlje_base_url ) ) {
			define( 'RLJE_BASE_URL', $rlje_base_url );
		}

		$content_base_url = ( ! empty( $this->theme_environment_settings['content_base_url'] ) ) ? $this->theme_environment_settings['content_base_url'] : 'https://dev-api.rlje.net/cms/acorn';
		if ( ! empty( $content_base_url ) ) {
			define( 'CONTENT_BASE_URL', $content_base_url );
		}

		$sailthru_customer_id = ( ! empty( $this->sailthru['customer_id'] ) ) ? $this->sailthru['customer_id'] : '';
		if ( ! empty( $sailthru_customer_id ) ) {
			define( 'SAILTHRU_CUSTOMER_ID', $sailthru_customer_id );
		}

		$rightsline_base_url = ( ! empty( $this->rightsline['base_url'] ) ) ? $this->rightsline['base_url'] : 'http://api.rightsline.com';
		if ( ! empty( $rightsline_base_url ) ) {
			define( 'RIGHTSLINE_BASE_URL', $rightsline_base_url );
		}

		$rightsline_auth_type   = ( ! empty( $this->rightsline['auth_type'] ) ) ? $this->rightsline['auth_type'] : '';
		$rightsline_auth_header = ( ! empty( $this->rightsline['auth_header'] ) ) ? $this->rightsline['auth_header'] : '';
		if ( ! empty( $rightsline_auth_type ) && ! empty( $rightsline_auth_header ) ) {
			define( 'RIGHTSLINE_AUTH_HEADER', $rightsline_auth_type . ' ' . $rightsline_auth_header );
		}

		// THIS NEEDS TO BE IN wp-config.php IN ORDER FOR IT TO WORK!!
		// IT IS TOO LATE TO CALL INSIDE OF THIS FILE
		// define( 'WP_DEBUG_LOG', true );
		// define( 'WP_DEBUG_DISPLAY', false );
		// define( 'SCRIPT_DEBUG', true );
		// // For query-monitor plugin.
		// define( 'WP_LOCAL_DEV', true );
		// define( 'WP_REDIS_HOST', 'redis' );
		// define( 'WP_REDIS_PORT', '6379' );
		// define( 'WP_REDIS_CLIENT', 'pecl' );
		// define( 'AWS_ACCESS_KEY_ID', $_SERVER['AWS_ACCESS_KEY_ID'] );
		// define( 'AWS_SECRET_ACCESS_KEY', $_SERVER['AWS_SECRET_KEY'] );
		// define( 'GLOBAL_SMTP_HOST', $_SERVER['GLOBAL_SMTP_HOST'] );
		// define( 'GLOBAL_SMTP_USER', $_SERVER['GLOBAL_SMTP_USER'] );
		// define( 'GLOBAL_SMTP_PASSWORD', $_SERVER['GLOBAL_SMTP_PASSWORD'] );
		// define( 'GLOBAL_SMTP_PORT', $_SERVER['GLOBAL_SMTP_PORT'] );
	}

	protected function theme_settings_include_files() {
		$this->theme_settings = get_option( 'rlje_theme_settings' );
		$current_theme        = ( ! empty( $this->theme_settings['current_theme'] ) ) ? $this->theme_settings['current_theme'] : 'acorn';
		switch ( $current_theme ) {
			case 'umc':
				require_once 'themes/umc/rlje-umc-theme.php';
				break;
			case 'acorn':
			default:
				require_once 'themes/acorn/rlje-acorn-theme.php';
		}
	}

	public function sanitize_callback( $data ) {
		$rlje_theme_settings = get_option( 'rlje_theme_settings' );
		$current_theme       = ( ! empty( $rlje_theme_settings['current_theme'] ) ) ? $rlje_theme_settings['current_theme'] : '';
		if ( $current_theme !== $data['current_theme'] ) {
			$clear_caches = array();
			$caches       = $this->rlje_redis_table->get_redis_caches();
			foreach ( $caches as $cache_key => $cache_value ) {
				$clear_caches[] = $cache_key;
			}

			$is_deleted = $this->rlje_redis_table->delete_redis_caches( $clear_caches );
		}

		add_settings_error( 'rlje-theme-settings', 'settings_updated', 'Successfully updated', 'updated' );

		return $data;
	}

}

$rlje_theme_settings = new RLJE_Theme_Settings();
