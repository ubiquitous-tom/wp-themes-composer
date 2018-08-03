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
		$area_one = ( ! empty( $this->section['area_one'] ) ) ?$this->section['area_one'] : array();
		$area_two = ( ! empty( $this->section['area_two'] ) ) ?$this->section['area_two'] : array();
		$areas = array_merge( $area_one, $area_two );
		?>
		<div id="drag-n-drop-section">
			<ul id="categories-list-draggable" class="sortable">
				<li class="header">Available Categories</li>
				<?php foreach ( $this->categories_items as $categories_item ) : ?>
					<?php if ( ! in_array( $categories_item->id, array_keys( $areas ) ) ) : ?>
					<li id="<?php echo esc_attr( $categories_item->id ); ?>" class="draggable ui-state-highlight categories-item"><?php echo esc_html( $categories_item->name ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>

			<ul id="homepage-layout">
				<li class="header">Homepage Layout</li>
				<li id="hero-carousel" class="disabled">Hero Carousel</li>
				<li>
					<ul id="area-one" class="sortable">
						<?php foreach ( $this->section['area_one'] as $area_one_item ) : ?>
						<li id="<?php echo esc_attr( $area_one_item->id ); ?>" class="draggable ui-state-highlight categories-item" style="width: 90%; right: auto; height: 18px; bottom: auto; z-index: 1;"><?php echo esc_html( $area_one_item->name ); ?></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li id="news-and-reviews" class="disabled">News and Reviews</li>
				<li>
					<ul id="area-two" class="sortable">
						<?php foreach ( $this->section['area_two'] as $area_two_item ) : ?>
						<li id="<?php echo esc_attr( $area_two_item->id ); ?>" class="draggable ui-state-highlight categories-item" style="width: 90%; right: auto; height: 18px; bottom: auto; z-index: 1;"><?php echo esc_html( $area_two_item->name ); ?></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li id="sub-footer" class="disabled">Sub Footer</li>
			</ul>
		</div>

		<input type="hidden" class="area-one" name="rlje_front_page_section[area_one]" value="<?php echo esc_attr( join( ',', array_keys( $this->section['area_one'] ) ) ); ?>">
		<input type="hidden" class="area-two" name="rlje_front_page_section[area_two]" value="<?php echo esc_attr( join( ',', array_keys( $this->section['area_two'] ) ) ); ?>">
		<?php
	}

	public function section_setting() {
		echo 'section_settings';
	}

	public function sanitize_callback( $data ) {
		$this->initialize_section_index();
		if ( ! empty( $data['area_one'] ) ) {
			$area_one_array = explode( ',', $data['area_one'] );
			$area_one = [];
			foreach ( $area_one_array as $area_one_id ) {
				foreach ( $this->categories_items as $categories_item ) {
					if ( strtolower( $categories_item->id ) === strtolower( $area_one_id ) ) {
						$area_one[ $area_one_id ] = $categories_item;
					}
				}
			}
			$data['area_one'] = $area_one;
		}

		if ( ! empty( $data['area_two'] ) ) {
			$area_two_array = explode( ',', $data['area_two'] );
			$area_two = [];
			foreach ( $area_two_array as $area_two_id ) {
				foreach ( $this->categories_items as $categories_item ) {
					if ( strtolower( $categories_item->id ) === strtolower( $area_two_id ) ) {
						$area_two[ $area_two_id ] = $categories_item;
					}
				}
			}
			$data['area_two'] = $area_two;
		}

		add_settings_error( 'rlje-theme-settings', 'settings_updated', 'Successfully updated', 'updated' );

		return $data;
	}
}

$rlje_section_position = new RLJE_Section_Position();
