<?php

require_once 'includes/rlje-hero.php';

class RLJE_Front_page {

	private $prefix = 'rlje_front_page';
	private $homepage = array();
	private $section = array();
	private $current_country = '';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_init', array( $this, 'register_admin_page' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_submenu' ) );

		add_filter( 'rlje_front_page_homepage_sanitizer', array( $this, 'load_new_country' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_rlje-front-page' === $hook ) {
			wp_enqueue_style( 'rlje-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css', array(), '3.3.4' );
			wp_enqueue_style( 'rlje-front-page', plugins_url( 'css/rlje-front-page.css', __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/rlje-front-page.css' ) );

			wp_enqueue_script( 'rlje-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array( 'jquery' ), '3.3.4', true );
			wp_enqueue_script( 'rlje-front-page', plugins_url( 'js/rlje-front-page.js', __FILE__ ), array( 'rlje-bootstrap' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/rlje-front-page.js' ), true );
			wp_enqueue_script( 'rlje-bc-swipe', plugins_url( 'js/bc-swipe.js', __FILE__ ), array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/bc-swipe.js' ), true );
		}
	}
	public function register_admin_page() {
		$register_setting_args = array(
			'description' => 'sanitize data and put them in the related country code array',
			'sanitize_callback' => array( $this, 'homepage_sanitizer' ),
		);
		register_setting( 'rlje_front_page', 'rlje_front_page_homepage', $register_setting_args );
		register_setting( 'rlje_front_page', 'rlje_front_page_section' );

		add_settings_section( 'homepage_section', 'Hero Settings', array( $this, 'homepage_settings' ), 'rlje-front-page' );
		add_settings_field( 'country_listing', 'Available Countries', array( $this, 'homepage_country_listing' ), 'rlje-front-page', 'homepage_section' );

		add_settings_section( 'position_section', 'Section Position Settings', array( $this, 'section_position_settings' ), 'rlje-section-position' );
		add_settings_field( 'country_listing', 'Available Countries', array( $this, 'homepage_country_listing' ), 'rlje-section-position', 'position_section' );
		add_settings_field( 'section_listing', 'Homepage Section Listing', array( $this, 'section_listing' ), 'rlje-section-position', 'position_section' );
		add_settings_field( 'section_setting', 'Homepage Section Settings', array( $this, 'section_setting' ), 'rlje-section-position', 'position_section' );
	}

	public function homepage_settings( $section ) {
		// echo section intro text here
		// echo '<p>id: ' . esc_html( $section['id'] ) . '</p>';                         // id: homepage_section
		// echo '<p>title: ' . apply_filters( 'the_title', $section['title'] ) . '</p>'; // title: Example settings section in reading
		// echo '<p>callback: ' . esc_html( $section['callback'] ) . '</p>';             // callback: homepage_section_callback_function
		$rlje_front_page_homepage = get_option( 'rlje_front_page_homepage', array() );
		$this->current_country    = $this->get_current_country();
		$country_code             = strtoupper( $this->current_country['code'] );
		$this->homepage           = ( array_key_exists( $country_code, $rlje_front_page_homepage ) ) ? $rlje_front_page_homepage[ $country_code ] : array();
		// var_dump( $rlje_front_page_homepage, $country_code, $this->homepage );
		?>
		<p>Currently displaying <strong><?php echo esc_html( $this->current_country['name'] ); ?></strong> Hero Settings</p>
		<?php
	}

	public function homepage_country_listing() {
		// echo home_url( add_query_arg( null, null ) );
		// $this->current_country = ( ! empty( $this->homepage['display_country'] ) ) ? $this->homepage['display_country'] : 'US';
		$countries = $this->get_countries();
		?>
		<select name="rlje_front_page_homepage[display_country]" id="display_country">
			<?php foreach ( $countries as $country ) : ?>
			<option value="<?php echo esc_attr( $country['code'] ); ?>" <?php selected( $country['code'], strtoupper( $this->current_country['code'] ) ); ?>>
				<?php echo esc_html( $country['name'] ); ?>
			</option>
			<?php endforeach; ?>
		</select>
		<input type="hidden" name="rlje_front_page_homepage[go_to_country]" value="<?php echo esc_attr( $this->current_country['code'] ); ?>">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Go to this country">
		<p class="description">Currently display Data from <?php echo esc_html( $countries[ $this->current_country ]['name'] ); ?></p>
		<?php
	}

	public function register_admin_menu() {
		add_menu_page(
			'Main Landing Page',
			'Front Page',
			'manage_options',
			'rlje-front-page',
			array( $this, 'add_menu_page' ),
			'dashicons-admin-home',
			5
		);
	}

	public function add_menu_page() {
		?>
		<div class="wrap" id="rlje-front-page">
			<h1>Main Landing Page</h1>
			<form method="post" action="options.php">
			<?php
			settings_fields( $this->prefix );
			do_settings_sections( 'rlje-front-page' );
			submit_button();
			?>
			</form>
		</div>
		<?php
	}

	public function register_admin_submenu() {
		add_submenu_page(
			'rlje-front-page',
			'Section Position Settings',
			'Section Position',
			'manage_options',
			'rlje-section-position',
			array( $this, 'add_submenu_page' )
		);
	}

	public function add_submenu_page() {
		?>
		<div class="wrap" id="news_reviews">
			<h1>Homepage Section Settings</h1>
			<form method="post" action="options.php">
			<?php
			settings_fields( $this->prefix );
			do_settings_sections( 'rlje-section-position' );
			submit_button();
			?>
			</form>
		</div>
		<?php
	}

	public function section_position_settings() {
		// echo 'Section Position Settings';
		$this->section = get_option( 'rlje_front_page_section', array() );
		var_dump($this->section);
	}

	public function section_listing() {
		echo 'section_listing';
	}

	public function section_settings() {
		echo 'section_settings';
	}

	public function load_new_country( $data ) {
		if ( ! empty( $data['go_to_country'] ) ) {
			$go_to_country =  $data['display_country'];
			unset( $data['go_to_country'] );
			wp_safe_redirect( add_query_arg( array( 'page' => 'rlje-front-page', 'country' => $go_to_country ), admin_url( 'admin.php' ) ) );
		}

		return $data;
	}

	protected function get_countries() {
		// https://acorn.dev/wp-admin/admin.php?page=rlje-front-page&country=mx
		// wp_safe_redirect( add_query_arg( array( 'page' => 'rlje-front-page', 'country' => 'mx' ), admin_url( 'admin.php' ) ) );
		$transient_key = 'rlje_country_code_list';
		// delete_transient( $transient_key );
		$countries     = get_transient( $transient_key );
		if ( false !== $countries ) {
			return $countries;
		} else {
			$countries     = array();
			$response      = wp_remote_get( 'https://api.rlje.net/cms/admin/countrycode' );
			$body          = wp_remote_retrieve_body( $response );
			$response_body = json_decode( $body );
			foreach ( $response_body as $country ) {
				$countries[ $country->CountryCode ]['timezone'] = $country->TimeZone;
				$countries[ $country->CountryCode ]['name']     = $country->CountryName;
				$countries[ $country->CountryCode ]['code']     = $country->CountryCode;
			}
			$updated = set_transient( $transient_key, $countries, 30 * MINUTE_IN_SECONDS );
			if ( $updated ) {
				return $countries;
			}
		}
	}

	protected function get_current_country() {
		$country   = ( ! empty( $_GET['country'] ) ) ? esc_attr( $_GET['country'] ) : 'us';
		// $country = ( ! empty( rljeApiWP_getCountryFilter() ) ) ? rljeApiWP_getCountryFilter() : 'US';
		$countries = $this->get_countries();
		$country   = $countries[ strtoupper( $country ) ];

		return $country;
	}

	public function homepage_sanitizer( $data ) {
		$new_data = get_option( 'rlje_front_page_homepage', array() );
		if ( ! empty( $data ) ) {
			$data = apply_filters( 'rlje_front_page_homepage_sanitizer', $data );

			$key              = $data['display_country'];
			$new_data[ $key ] = $data;
		}

		return $new_data;
	}
}

$rlje_front_page = new RLJE_Front_page();
