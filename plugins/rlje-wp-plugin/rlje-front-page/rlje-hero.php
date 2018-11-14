<?php

class RLJE_Hero {

	protected $current_country = [];
	protected $homepage = [];
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_admin_page' ) );
		add_action( 'rlje_homepage_top_section_content', array( $this, 'display_hero_carousel' ) );

		add_filter( 'rlje_front_page_homepage_sanitizer', array( $this, 'delete_hero_cache' ) );
		add_filter( 'rlje_api_get_country_code', array( $this, 'get_admin_country_code' ) );
		add_filter( 'rlje_redis_api_cache_groups', array( $this, 'add_carousel_cache_table_list' ) );
		// add_filter( 'wp_kses_allowed_html', array( $this, 'allow_carousel_data_attributes' ), 10, 2 );
	}

	public function register_admin_page() {
		add_settings_section( 'homepage_section', 'Hero Settings', array( $this, 'homepage_settings' ), 'rlje-front-page' );
		add_settings_field( 'country_listing', 'Available Countries', array( $this, 'homepage_country_listing' ), 'rlje-front-page', 'homepage_section' );

		add_settings_field( 'hero_preview', 'Hero Preview', array( $this, 'homepage_hero_preview' ), 'rlje-front-page', 'homepage_section' );
		add_settings_field( 'hero_expiration', 'Hero Cache Expiration', array( $this, 'homepage_hero_expiration' ), 'rlje-front-page', 'homepage_section' );
		add_settings_field( 'hero_clear_cache', 'Hero Clear Cache', array( $this, 'homepage_hero_clear_cache' ), 'rlje-front-page', 'homepage_section' );
	}

	public function homepage_settings( $section ) {
		$rlje_front_page_homepage = get_option( 'rlje_front_page_homepage' );
		$this->current_country    = $this->get_current_country();
		$country_code             = strtoupper( $this->current_country['code'] );
		$this->homepage           = ( array_key_exists( $country_code, $rlje_front_page_homepage ) ) ? $rlje_front_page_homepage[ $country_code ] : array();
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
		<!-- <input type="hidden" name="rlje_front_page_homepage[go_to_country]" value="<?php echo esc_attr( $this->current_country['code'] ); ?>"> -->
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Go to this country">
		<p class="description">Currently display hero carousel from <?php echo esc_html( $countries[ $this->current_country['code'] ]['name'] ); ?></p>
		<?php
	}

	public function homepage_hero_preview() {
		echo $this->display_hero_carousel();
	}

	public function homepage_hero_expiration() {
		$hero_expiration = ( ! empty( $this->homepage['hero_expiration'] ) ) ? $this->homepage['hero_expiration'] : 5 * MINUTE_IN_SECONDS;
		?>
		<input type="text" name="rlje_front_page_homepage[hero_expiration]" id="hero-expiration" class="regular-text" value="<?php echo absint( $hero_expiration ); ?>" placeholder="Please enter time in seconds">
		<p class="description">Default homepage hero carousel expiration time is set to 5 minutes (300 seconds)</p>
		<?php
	}

	public function homepage_hero_clear_cache() {
		$current_country = $this->get_current_country();
		$country_name = $current_country['name'];
		$country_code = $current_country['code'];
		?>
		<p class="description">This button will clear the carousel cache for the homepage of <strong><?php echo esc_html( $country_name ); ?></strong></p>
		<?php
		submit_button( 'Delete Hero Cache', 'delete' );
	}

	public function display_hero_carousel() {
		$transient_key = $this->get_transient_key( 'rlje_homepage_hero_carousel' );
		$hero          = get_transient( $transient_key );
		if ( false !== $hero ) {
		// 	// $allowed_html = wp_kses_allowed_html( 'post' );
		// 	// echo wp_kses( $hero, $allowed_html );
			echo $hero;
		} else {
			echo $this->build_hero_carousel();
		}
	}

	public function build_hero_carousel( $echo = false ) {
		$hero_expiration = ( ! empty( $this->homepage['hero_expiration'] ) ) ? $this->homepage['hero_expiration'] : 5 * MINUTE_IN_SECONDS;
		$transient_key   = $this->get_transient_key( 'rlje_homepage_hero_carousel' );

		$carousel      = rljeApiWP_getHomeItems( 'carousel' );
		$data_carousel = ( ! empty( $carousel->media ) && is_array( $carousel->media ) ) ? $carousel->media : array();

		if ( 0 < count( $data_carousel ) ) {
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/hero.php';
			$hero = ob_get_clean();
		} else {
			?>
			<div class="container">
				<div class="row">
					<div class="alert alert-info text-center">No results ...</div><br>
				</div>
			</div>
			<?php
		}

		$updated = set_transient( $transient_key, $hero, $hero_expiration );
		if ( $updated ) {
			return $hero;
		}
	}

	public function delete_hero_cache( $data ) {
		if ( ! empty( $_POST['submit'] ) && ( 'Delete Hero Cache' === $_POST['submit'] ) ) {
			$transient_key = $this->get_transient_key( 'rlje_homepage_hero_carousel' );
			$hero          = get_transient( $transient_key );
			if ( false !== $hero ) {
				delete_transient( $transient_key );
			}
		}
		$stuff = get_transient( $transient_key );

		return $data;
	}

	public function get_admin_country_code( $country_code ) {
		if ( is_admin() ) {
			$country = $this->get_current_country();
			$country_code = strtolower( $country['code'] );
		}

		return $country_code;
	}

	public function add_carousel_cache_table_list( $cache_list ) {
		$cache_list[] = 'rlje_homepage_hero_carousel';

		return $cache_list;
	}

	public function allow_carousel_data_attributes( $allowed_html, $context ) {
		if ( 'post' === $context ) {
			$allowed_html[ 'li' ][ 'data-target' ]   = true;
			$allowed_html[ 'li' ][ 'data-slide-to' ] = true;
		}

		return $allowed_html;
	}

	protected function get_transient_key( $key_prefix ) {
		$current_country = $this->get_current_country();
		$country         = strtolower( $current_country['code'] );
		$transient_key   = implode( '_', array( $key_prefix, $country ) );

		return $transient_key;
	}

	protected function get_current_country() {
		$country = ( ! empty( $_GET['country'] ) ) ? esc_attr( $_GET['country'] ) : 'us';
		// $country = ( ! empty( rljeApiWP_getCountryFilter() ) ) ? rljeApiWP_getCountryFilter() : 'US';
		$countries = $this->get_countries();
		$country   = $countries[ strtoupper( $country ) ];

		return $country;
	}

	protected function get_countries() {
		// https://acorn.dev/wp-admin/admin.php?page=rlje-front-page&country=mx
		// wp_safe_redirect( add_query_arg( array( 'page' => 'rlje-front-page', 'country' => 'mx' ), admin_url( 'admin.php' ) ) );
		$transient_key = 'rlje_country_code_list';
		// delete_transient( $transient_key );
		$countries = get_transient( $transient_key );
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
}

$rlje_hero = new RLJE_Hero();
