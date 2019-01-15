<?php

class RLJE_Widget {

	protected $nonce = 'rlje-widget-nonce-)#$*!($&0943';
	protected $footer_areas = array(
		array(
			'name'          => 'Footer Area 1',
			'id'            => 'footer-area-1',
			'description'   => 'Left widget area for the footer',
			'class'         => '',
			'before_widget' => '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		),
		array(
			'name'          => 'Footer Area 2',
			'id'            => 'footer-area-2',
			'description'   => 'Middle widget area for the footer',
			'class'         => '',
			'before_widget' => '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		),
		array(
			'name'          => 'Footer Area 3',
			'id'            => 'footer-area-3',
			'description'   => 'Right widget area for the footer',
			'class'         => '',
			'before_widget' => '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5>',
			'after_title'   => '</h5>',
		),
	);

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_set_locale', array( $this, 'set_locale' ) );
		add_action( 'wp_ajax_nopriv_set_locale', array( $this, 'set_locale' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'rlje_footer_widget_area', array( $this, 'display_footer_widget' ) );

		add_filter( 'wp_nav_menu_items', [ $this, 'add_language_dropdown' ], 10, 2 );
	}

	public function enqueue_scripts() {
		$js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/language-dropdown.js' ) );

		wp_enqueue_script( 'rlje-language-dropdown', plugins_url( '/js/language-dropdown.js', __FILE__ ), array( 'jquery', 'cookies-js', 'main-js' ), $js_ver, true );

		$locale_object = [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( $this->nonce ),
		];
		wp_localize_script( 'main-js', 'rlje_locale_object', $locale_object );
	}

	public function widgets_init() {
		$this->footer_areas = apply_filters( 'rlje_widget_footer_areas', $this->footer_areas );
		foreach ( $this->footer_areas as $footer_area ) {
			register_sidebar( $footer_area );
		}
	}

	public function display_footer_widget() {
		?>
		<div class="sub-footer">
			<div class="container" style="margin-bottom: 45px; margin-top: 45px;">
			<?php
			foreach ( $this->footer_areas as $widget ) {
				dynamic_sidebar( $widget['id'] );
			}
			?>
			</div>
		</div>
		<?php
	}

	public function add_language_dropdown( $items, $args ) {
		if ( strpos( $args->menu->slug, 'help' ) !== false ) {
			$extra_li = $this->get_language_dropdown_html();

			$items .= $extra_li;
		}

		return $items;
	}

	public function get_language_dropdown_html() {
		ob_start();
		?>
			<li>
				<label class="atv-locale" for="atv-locale"><?php esc_html_e( 'language', 'acorntv' ); ?>: </label>
				<?php
					$available_locales = rljeApiWP_getLocale();
					$current_locale    = get_locale();
				?>
				<select name="atv_locale" id="atv-locale">
					<?php foreach ( $available_locales as $locale_key => $locale_language ) : ?>
					<option value="<?php echo esc_attr( $locale_key ); ?>" <?php selected( $current_locale, $locale_key ); ?>>
						<?php echo esc_html( json_decode( '"' . $locale_language . '"' ) ); ?>
					</option>
					<?php endforeach; ?>
				</select>
			</li>
		<?php
			$html = ob_get_clean();

		return $html;
	}

	public function set_locale() {
		if ( ! wp_verify_nonce( $_POST['nonce'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		$new_locale        = ( ! empty( $_POST['rlje_locale'] ) ) ? $_POST['rlje_locale'] : '';
		$location_pathname = ( ! empty( $_POST['location_pathname'] ) ) ? $_POST['location_pathname'] : null;
		if ( ! empty( $new_locale ) ) {
			$parse_url     = wp_parse_url( home_url() );
			$host_names    = explode( '.', $parse_url['host'] );
			$cookie_domain = '.' . $host_names[ count( $host_names ) - 2 ] . '.' . $host_names[ count( $host_names ) - 1 ];
			// setcookie( 'ATVLocale', $new_locale, time() + YEAR_IN_SECONDS, COOKIEPATH, $cookie_domain );
			// wp_safe_redirect( home_url() ); /* Redirect browser to homepage DOESN'T WORK HERE */
			$current_location_pahtname = ( ! empty( $_POST['locationPathname'] ) ) ? $_POST['locationPathname'] : '';
			$data                      = array(
				'redirectTo'     => home_url( $current_location_pahtname ),
				'redirectToPath' => $current_location_pahtname,
				'locale'         => $new_locale,
				'cookie_domain'  => $cookie_domain,
			);
			wp_send_json_success( $data );
		}
	}
}

$rlje_widget = new RLJE_Widget();

require_once 'newsletter/rlje-newsletter-widget.php';
