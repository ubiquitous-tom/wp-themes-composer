<?php

class RLJE_Section_Position extends RLJE_Front_page {

	protected $section = array();
	protected $categories_home;
	protected $categories_items;
	protected $browse_id_list_availables;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'initialize_section_index' ) );
		add_action( 'admin_init', array( $this, 'display_options' ) );
		add_action( 'admin_menu', array( $this, 'add_front_page_submenu' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'homepage-hero_page_rlje-section-position' === $hook ) {
			wp_enqueue_style( 'jquery-ui-core' , '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css', array(), '1.11.4' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			// wp_enqueue_script( 'jquery-ui-droppable' );

			$section_css_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/admin-section-position.css' ) );
			$section_js_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/admin-section-position.js' ) );
			wp_enqueue_style( 'rlje-admin-section-position' , plugins_url( 'css/admin-section-position.css', __FILE__ ), array(), '1.0.0' );
			wp_enqueue_script( 'rlje-admin-section-position', plugins_url( 'js/admin-section-position.js', __FILE__ ), array( 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-sortable' ), $section_js_version, true );
		}
	}

	public function initialize_section_index() {
		$this->categories_home           = rljeApiWP_getHomeItems( 'categories' );
		$this->categories_items          = ( isset( $this->categories_home->options ) ) ? $this->categories_home->options : array();
		$this->browse_id_list_availables = apply_filters( 'atv_get_browse_genres_availables', '' );
	}

	public function display_options() {
		register_setting( 'rlje_front_page', 'rlje_front_page_section', array( $this, 'sanitize_callback' ) );

		add_settings_section( 'position_section', 'Section Position Settings', array( $this, 'section_position_settings' ), 'rlje-section-position' );
		add_settings_field( 'country_listing', 'Available Countries', array( $this, 'homepage_country_listing' ), 'rlje-section-position', 'position_section' );
		add_settings_field( 'section_listing', 'Homepage Section Listing', array( $this, 'section_listing' ), 'rlje-section-position', 'position_section' );
		add_settings_field( 'section_setting', 'Homepage Section Settings', array( $this, 'section_setting' ), 'rlje-section-position', 'position_section' );
	}

	public function add_front_page_submenu() {
		add_submenu_page(
			'rlje-front-page',
			'Section Position Settings',
			'Section Position',
			'manage_options',
			'rlje-section-position',
			array( $this, 'rlje_section_position_page' )
		);
	}

	public function rlje_section_position_page() {
		?>
		<div class="wrap" id="news_reviews">
			<h1>Homepage Section Settings</h1>
			<?php settings_errors(); ?>
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
		echo 'Rearrange Section Position for the homepage for different country';
		// delete_option( 'rlje_front_page_section' );
		$this->section = get_option( 'rlje_front_page_section' );
		$this->current_country    = $this->get_current_country();
		$country_code             = strtoupper( $this->current_country['code'] );
		var_dump($this->section);
	}

	public function stuff_homepage_country_listing() {
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
		<p class="description">Currently display Data from <?php echo esc_html( $countries[ $this->current_country['code'] ]['name'] ); ?></p>
		<?php
	}

	public function section_listing() {
		// echo 'section_listing';

		$section_position = ( ! empty( $this->section['section_position'] ) ) ? $this->section['section_position'] : array();
		if ( empty( $section_position ) ) {
			$section_position = $this->categories_items;
			$news_and_reviews = new stdClass();
			$news_and_reviews->id = 'news-and-reviews';
			$news_and_reviews->name = 'News And Reviews';
			$section_position[] = $news_and_reviews;
		}
		?>
		<div id="drag-n-drop-section">
			<ul id="homepage-layout">
				<li id="hero-carousel" class="disabled">Hero Carousel</li>
				<?php foreach ( $section_position as $section_position_item ) : ?>
					<?php $classes = ( 'news-and-reviews' === $section_position_item->id ) ? '' : 'ui-state-highlight categories-item'; ?>
				<li id="<?php echo esc_attr( $section_position_item->id ); ?>" class="<?php echo esc_attr( $classes ); ?>"><?php echo esc_html( $section_position_item->name ); ?></li>
				<?php endforeach; ?>
				<li id="sub-footer" class="disabled">Sub Footer</li>
			</ul>
		</div>

		<input type="hidden" id="section-position-layout" name="rlje_front_page_section[section_position]" value="<?php echo esc_attr( join( ',', array_keys( $this->section['section_position'] ) ) ); ?>">
		<?php
	}

	public function section_setting() {
		echo 'section_settings';
	}

	public function sanitize_callback( $data ) {
		$this->initialize_section_index();
		if ( ! empty( $data['section_position'] ) ) {
			$section_position_array = explode( ',', $data['section_position'] );
			$section_position = [];
			$is_bottom_section = false;
			foreach ( $section_position_array as $section_position_id ) {
				foreach ( $this->categories_items as $categories_item ) {
					// Home Featured Section or Home Spotlight Section.
					if ( ( strtolower( $categories_item->id ) === strtolower( $section_position_id ) ) ) {
						$categories_item->section_type = ( false === $is_bottom_section ) ? 'home-featured' : 'home-spotlight';
						$section_position[ $section_position_id ] = $categories_item;
					}

					// News And Reviews Section.
					if ( 'news-and-reviews' === strtolower( $section_position_id ) ) {
						$news_and_reviews_item = new stdClass();
						$news_and_reviews_item->id = 'news-and-reviews';
						$news_and_reviews_item->name = 'News And Reviews';
						$news_and_reviews_item->section_type = 'news-and-reviews';
						$section_position[ $section_position_id ] = $news_and_reviews_item;

						// From here on out it's for Home Spotlight Section.
						$is_bottom_section = true;
					}
				}
			}
			$data['section_position'] = $section_position;
		}

		add_settings_error( 'rlje-theme-settings', 'settings_updated', 'Successfully updated', 'updated' );

		return $data;
	}
}

$rlje_section_position = new RLJE_Section_Position();
