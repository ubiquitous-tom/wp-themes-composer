<?php

class RLJE_Front_page {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', [ $this, 'register_homepage_settings' ] );
		add_action( 'admin_init', array( $this, 'register_admin_page' ) );
		add_action( 'admin_menu', array( $this, 'register_homepage_menu' ) );
		add_action( 'admin_menu', [ $this, 'hero_carousel_menu' ] );
		add_action( 'admin_menu', [ $this, 'homepage_text_menu' ] );

		add_filter( 'rlje_redis_api_cache_groups', array( $this, 'add_country_list_cache_table_list' ) );
		require_once 'rlje-section-position.php';
		require_once 'rlje-hero.php';
		require_once 'rlje-text-part.php';
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

	public function register_homepage_settings() {
		register_setting( 'rlje-front-page', 'rlje_front_page_homepage', array( $this, 'sanitize_callback' ) );
	}

	public function register_homepage_menu() {
		add_menu_page(
			'Homepage Settings',
			'Homepage',
			'manage_options',
			'rlje-front-page',
			null,
			'dashicons-admin-home',
			5
		);
	}

	public function hero_carousel_menu() {
		add_submenu_page(
			'rlje-front-page',
			'Hero Carousel Settings',
			'Hero Carousel',
			'manage_options',
			'rlje-front-page',
			[ $this, 'render_hero_carousel_settings' ]
		);
	}

	public function render_hero_carousel_settings() {
		?>
		<div class="wrap" id="rlje-front-page">
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'rlje-front-page' );
				do_settings_sections( 'rlje-front-page' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	public function add_country_list_cache_table_list( $cache_list ) {
		$cache_list[] = 'rlje_country_code_list';

		return $cache_list;
	}

	public function sanitize_callback( $data ) {
		if ( ! empty( $_POST['submit'] ) && ( 'Delete Hero Cache' === $_POST['submit'] ) ) {
			$data = apply_filters( 'rlje_front_page_homepage_sanitizer', $data );
		}

		if ( ! empty( $_POST['submit'] ) && ( 'Go to this country' === $_POST['submit'] ) ) {
			$go_to_country = $data['display_country'];
			unset( $data['go_to_country'] );

			$query_args = array(
				'page'    => 'rlje-front-page',
				'country' => $go_to_country,
			);
			wp_safe_redirect( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
			exit();
		}

		$rlje_front_page_homepage = get_option( 'rlje_front_page_homepage' );
		$key                      = $data['display_country'];
		$new_data[ $key ]         = $data;
		$data                     = array_merge( $rlje_front_page_homepage, $new_data );

		return $data;
	}
}

$rlje_front_page = new RLJE_Front_page();
