<?php

require_once 'includes/rlje-header-walker-nav-menu.php';
require_once 'includes/rlje-footer-walker-nav-menu.php';

class RLJE_Theme_Menu_Settings {

	private $theme_menu_settings;
	private $is_user_logged_and_active;
	private $http_user_agent;

	public function __construct() {
		$this->is_user_logged_and_active = ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) );
		$this->http_user_agent           = apply_filters( 'atv_browser_detection', $_SERVER['HTTP_USER_AGENT'] );

		add_action( 'admin_init', array( $this, 'display_options' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'rlje_before_header_navigation', array( $this, 'is_user_logged_in_and_active' ) );
		add_action( 'rlje_after_header_navigation', array( $this, 'active_features' ), 10 );
		add_action( 'rlje_after_header_navigation', array( $this, 'upgrade_message' ), 15 );
		add_action( 'rlje_header_navigation', array( $this, 'display_header_navigation' ) );
		add_action( 'rlje_footer_navigation', array( $this, 'display_footer_navigation' ) );

		add_filter( 'nav_menu_item_id', array( $this, 'remove_nav_menu_item_id' ), 10, 4 );
		add_filter( 'nav_menu_css_class', array( $this, 'remove_nav_menu_css_class' ), 10, 4 );
	}

	public function enqueue_scripts() {
		if ( $this->is_user_logged_and_active && $http_user_agent ) {
			$upgrade_message_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'navigation/js/upgrade-message.js' ) );
			wp_enqueue_script( 'upgrade-message', plugins_url( 'js/upgrade-message.js', __FILE__ ), array( 'jquery' ), $upgrade_message_version, true );
			$url_parts       = parse_url( site_url() );
			$domain          = '.' . $url_parts['host'];
			$upgrade_message = array(
				'key'    => 'dismissUpgradeMessage',
				'value'  => 'on',
				'end'    => 604800,
				'path'   => '/',
				'domain' => $domain,
			);
			wp_localize_script( 'upgrade-message', 'upgrade_message', $upgrade_message );
		}
	}

	public function display_options() {
		// register_setting( 'rlje_theme_menu_section', 'rlje_theme_menu_settings' );

		// add_settings_section( 'rlje_theme_menu_section', 'Menu Options', array( $this, 'display_rlje_options_content' ), 'rlje-theme-settings' );
		// add_settings_field( 'header_menu', 'Header Menu', array( $this, 'display_header_menu' ), 'rlje-theme-settings', 'rlje_theme_menu_section' );
		// add_settings_field( 'footer_menu', 'Footer Menu', array( $this, 'display_footer_menu' ), 'rlje-theme-settings', 'rlje_theme_menu_section' );
	}

	public function is_user_logged_in_and_active() {
		// $this->is_user_logged_and_active = ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) );
		// Leave the else value empty to production, now is .dev because it is not implemented in prod yet (used in uat.acorn.tv).
		$environment = apply_filters( 'atv_get_extenal_subdomain', '' );
		ob_start();
		if ( $this->is_user_logged_and_active ) {
			$web_payment_edit = rljeApiWP_getWebPaymentEdit( $_COOKIE['ATVSessionCookie'] );
			require_once plugin_dir_path( __FILE__ ) . 'partials/logged-in.php';
		} else {
			require_once plugin_dir_path( __FILE__ ) . 'partials/not-logged-in.php';
		}
		$html = ob_get_clean();

		echo $html;
	}

	public function active_features() {
		$future_date          = rljeApiWP_getFutureDate();
		$country_filter       = rljeApiWP_getCountryFilter();
		$is_video_debugger_on = rljeApiWP_isVideoDebuggerOn();

		if ( ( empty( $future_date ) ) && ( empty( $country_filter ) ) && ( empty( $is_video_debugger_on ) ) ) {
			return;
		}

		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'partials/active-features.php';
		$html = ob_get_clean();

		echo $html;
	}

	public function upgrade_message() {
		// $this->http_user_agent = apply_filters( 'atv_browser_detection', $_SERVER['HTTP_USER_AGENT'] );
		if ( $this->is_user_logged_and_active && $this->http_user_agent ) {
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'partials/upgrade-message.php';
			$html = ob_get_clean();

			echo $html;
		}
	}

	public function display_header_menu() {
		$menus       = get_registered_nav_menus();
		$header_menu = ( ! empty( $this->theme_menu_settings['header_menu'] ) ) ? $this->theme_menu_settings['header_menu'] : '';
		?>
		<select name="rlje_theme_settings[header_menu]" id="header-menu" class="regular-text">
			<?php foreach ( $menus as $key => $value ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $header_menu, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function display_footer_menu() {
		$menus       = get_registered_nav_menus();
		$footer_menu = ( ! empty( $this->theme_menu_settings['footer_menu'] ) ) ? $this->theme_menu_settings['footer_menu'] : '';
		?>
		<select name="rlje_theme_settings[footer_menu]" id="footer-menu" class="regular-text">
			<?php foreach ( $menus as $key => $value ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $footer_menu, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function display_header_navigation() {
		$search_form = get_search_form( false );
		$item_wrap   = '<ul class="%2$s">' . $search_form . '%3$s</ul>';
		$menu_args   = array(
			'menu_class'      => 'nav navbar-nav',
			'menu_id'         => '',
			'container_class' => 'navbar-collapse side-collapse in',
			'container_id'    => '',
			'fallback_cb'     => false,
			'items_wrap'      => $item_wrap,
			'walker'          => new RLJE_Header_Walker_Nav_Menu(),
			'theme_location'  => 'primary',
		);
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/header-nav.php';
		$html = ob_get_clean();

		echo $html;
	}

	public function display_footer_navigation() {
		$menu_args = array(
			'menu_class'      => '',
			'menu_id'         => '',
			'container'       => '',
			'container_class' => '',
			'container_id'    => '',
			'fallback_cb'     => false,
			'items_wrap'      => '<ul>%3$s</ul>',
			'theme_location'  => 'secondary',
		);
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/footer-nav.php';
		$html = ob_get_clean();

		echo $html;
	}

	public function remove_nav_menu_item_id( $item_id, $item, $args, $depth ) {
		if ( in_array( $args->theme_location, array( 'primary', 'secondary' ), true ) ) {
			if ( strpos( $item_id, 'menu-item' ) !== false ) {
				$item_id = '';
			}
		}

		return $item_id;
	}

	public function remove_nav_menu_css_class( $classes, $item, $args, $depth ) {
		if ( in_array( $args->theme_location, array( 'primary', 'secondary' ), true ) ) {
			foreach ( $classes as $key => $class ) {
				if ( strpos( $class, 'menu-item' ) !== false ) {
					unset( $classes[ $key ] );
				}
			}
		}

		return $classes;
	}
}

$rlje_theme_menu_settings = new RLJE_Theme_Menu_Settings();
