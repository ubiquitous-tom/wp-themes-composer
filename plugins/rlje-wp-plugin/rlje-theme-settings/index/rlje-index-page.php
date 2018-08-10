<?php

class RLJE_Index_Page {

	protected $theme_text_settings;
	protected $theme_plugins_settings;
	protected $categories_home;
	protected $categories_items;
	protected $spotlight_name;
	protected $browse_id_list_availables;
	protected $home_sections;
	protected $nonce = 'atv#contentPage@token_nonce';

	public function __construct() {
		add_action( 'init', array( $this, 'initialize_index' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_paginate', array( $this, 'ajax_carousel_pagination' ) );
		add_action( 'wp_ajax_nopriv_paginate', array( $this, 'ajax_carousel_pagination' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_home_loggedin_featured' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_home_featured' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_home_spotlights' ) );
		add_action( 'rlje_homepage_bottom_section_content', array( $this, 'display_callback' ) );
	}

	public function initialize_index() {
		$this->theme_text_settings       = get_option( 'rlje_theme_text_settings' );
		$this->theme_plugins_settings    = get_option( 'rlje_theme_plugins_settings' );
		$this->home_sections             = get_option( 'rlje_front_page_section', array() );
		$this->categories_home           = rljeApiWP_getHomeItems( 'categories' );
		$this->categories_items          = ( isset( $this->categories_home->options ) ) ? $this->categories_home->options : array();
		$this->browse_id_list_availables = apply_filters( 'atv_get_browse_genres_availables', '' );
	}

	public function enqueue_scripts() {
		if ( is_home() || is_front_page() ) {
			// $orderby_js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/orderby.js' ) );
			$pagination_js_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/carousel-pagination.js' ) );

			// wp_enqueue_script( 'browse-orderby-js', plugins_url( 'js/orderby.js', __FILE__ ), array( 'jquery' ), $js_ver, true );
			// Special js hook to update carousel pagination image url to use the right one for umc.
			wp_enqueue_script( 'rlje-carousel-pagination-js', plugins_url( '/js/carousel-pagination.js', __FILE__ ), array( 'jquery' ), $pagination_js_ver, true );

			$browse_object = array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'home_url'  => home_url(),
				'image_url' => rljeApiWP_getImageUrlFromServices( '' ),
				'token'     => wp_create_nonce( $this->nonce ),
			);
			wp_localize_script( 'rlje-carousel-pagination-js', 'carousel_pagination_object', $browse_object );
		}
	}

	public function ajax_carousel_pagination() {
		if ( ! wp_verify_nonce( $_POST['token'], $this->nonce ) ) {
			die( 'Action Not Allow!' );
		}

		$content = ( ! empty( $_POST['content'] ) ) ? $_POST['content'] : null;
		$page    = ( ! empty( $_POST['page'] ) ) ? $_POST['page'] : null;

		$data = rljeApiWP_getContentPageItems( $content, $page );
		wp_send_json_success( $data );
	}

	public function display_home_loggedin_featured() {
		if ( is_home() || is_front_page() ) :
			ob_start();
			?>
		<section class="home-featured">
			<div class="container">
			<?php
			if ( ! empty( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) :
				$watch_spotlight_items = apply_filters( 'atv_get_watch_spotlight_items', 'recentlyWatched' );
				if ( 0 < count( $watch_spotlight_items ) ) :
			?>
				<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
				<div class="col-md-12">
				<?php
					set_query_var( 'carousel-items', $watch_spotlight_items );
					get_template_part( 'partials/section-generic-carousel' );
				?>
				</div>
				<?php
				endif;

				$watchlist_spotlight_items = apply_filters( 'atv_get_watch_spotlight_items', 'watchlist' );
				if ( 0 < count( $watchlist_spotlight_items ) ) :
				?>
				<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
				<div class="col-md-12">
				<?php
					set_query_var( 'carousel-items', $watchlist_spotlight_items );
					get_template_part( 'partials/section-generic-carousel' );
				?>
				</div>
				<?php
				endif;
			endif;
			?>
			</div>
		</section>
			<?php
		$html = ob_get_clean();
		echo $html;
		endif;
	}

	public function display_home_featured() {
		if ( is_home() || is_front_page() ) :
			if ( empty( $this->home_sections['section_position'] ) ) {
				return;
			}

			$home_featured = [];
			foreach ( $this->home_sections['section_position'] as $section_position ) {
				if ( 'news-and-reviews' !== $section_position->id ) {
					$home_featured[] = $section_position;
				} else {
					break;
				}
			}

			ob_start();
			?>
		<section class="home-featured">
			<div class="container">
			<?php
			/*if ( ! empty( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) :
				$watch_spotlight_items = apply_filters( 'atv_get_watch_spotlight_items', 'recentlyWatched' );
				if ( 0 < count( $watch_spotlight_items ) ) :
			?>
				<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
				<div class="col-md-12">
				<?php
					set_query_var( 'carousel-items', $watch_spotlight_items );
					get_template_part( 'partials/section-generic-carousel' );
				?>
				</div>
				<?php
				endif;

				$watchlist_spotlight_items = apply_filters( 'atv_get_watch_spotlight_items', 'watchlist' );
				if ( 0 < count( $watchlist_spotlight_items ) ) :
				?>
				<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
				<div class="col-md-12">
				<?php
					set_query_var( 'carousel-items', $watchlist_spotlight_items );
					get_template_part( 'partials/section-generic-carousel' );
				?>
				</div>
				<?php
				endif;
			endif;*/

			for ( $i = 0; $i < 2 && isset( $home_featured[ $i ] ); $i++ ) :
				$spotlight            = $home_featured[ $i ];
				$this->spotlight_name = ( ! empty( $spotlight->name ) ) ? $spotlight->name : '';
				?>
				<!-- <?php echo strtoupper( $this->spotlight_name ); ?> SPOTLIGHT-->
				<div class="col-md-12">
				<?php
					set_query_var(
						'carousel-section', array(
							'title'           => $this->spotlight_name,
							'categoryObj'     => $spotlight,
							'showViewAllLink' => ( isset( $this->browse_id_list_availables[ $spotlight->id ] ) ),
						)
					);
					get_template_part( 'partials/section-carousel-pagination' );
				?>
				</div>
			<?php endfor; ?>
			</div>
		</section>
			<?php
			$html = ob_get_clean();
			echo $html;
			endif;
	}

	public function display_home_spotlights() {
		if ( is_home() || is_front_page() ) :
			if ( empty( $this->home_sections['section_position'] ) ) {
				return;
			}

			$home_spotlights = [];
			$is_found = false;
			foreach ( $this->home_sections['section_position'] as $section_position ) {
				if ( 'news-and-reviews' === $section_position->id || $is_found ) {
					$is_found = true;
					$home_spotlights[] = $section_position;
				}
			}

			ob_start();
			?>
		<section class="home-spotlights">
			<div class="container">
				<?php
				for ( $i =  2; $i < count( $home_spotlights ); $i++ ) :
					$spotlight            = $home_spotlights[ $i ];
					$this->spotlight_name = ( ! empty( $spotlight->name ) ) ? $spotlight->name : '';
					?>
			<!-- <?php echo strtoupper( $this->spotlight_name ); ?> SPOTLIGHT -->
				<div class="col-md-12">
					<?php
						set_query_var(
							'carousel-section', array(
								'title'           => $this->spotlight_name,
								'categoryObj'     => $spotlight,
								'showViewAllLink' => ( isset( $this->browse_id_list_availables[ $spotlight->id ] ) ),
							)
						);
					get_template_part( 'partials/section-carousel-pagination' );
					?>
				</div>
					<?php
					endfor;
				?>
			</div>
		</section>
			<?php
			$html = ob_get_clean();
			echo $html;
		endif;
	}

	public function display_callback() {
		if ( is_home() || is_front_page() ) :
			$environment = apply_filters( 'atv_get_extenal_subdomain', '' );
			$is_activated = ( ! intval( $this->theme_plugins_settings['home_callout'] ) ) ? intval( $this->theme_plugins_settings['home_callout'] ) : 1;
			if ( ! $is_activated ) {
				return;
			}

			$callout_one = ( ! empty( $this->theme_text_settings['callout']['one'] ) ) ? $this->theme_text_settings['callout']['one'] : array();
			$callout_one_text = ( ! empty( $callout_one['text'] ) ) ? $callout_one['text'] : 'Available on Roku, Apple TV, Samsung Smart TV, iPhone, iPad, web and more.';
			$callout_one_link = ( ! empty( $callout_one['link'] ) ) ? $callout_one['link'] : home_url( '/' );
			$callout_one_link_text = ( ! empty( $callout_one['link_text'] ) ) ? $callout_one['link_text'] : 'Learn More';

			$callout_two = ( ! empty( $this->theme_text_settings['callout']['two'] ) ) ? $this->theme_text_settings['callout']['two'] : array();
			$callout_two_text = ( ! empty( $callout_two['text'] ) ) ? $callout_two['text'] : 'Over 1,800 hours of programming, including 60 shows you won\'t find anywhere else.';
			$callout_two_link = ( ! empty( $callout_two['link'] ) ) ? $callout_two['link'] : home_url( '/' );
			$callout_two_link_text = ( ! empty( $callout_two['link_text'] ) ) ? $callout_two['link_text'] : 'Start Your Free Trial';

			ob_start();
			?>
		<section class="home-callout">
			<div class="container">
				<div class="col-md-12 home-callout-body">
					<div class="col-md-6" id="border-carousel">
						<div class="home-callout-content">
							<img src="https://api.rlje.net/acorn/artwork/size/devices-icon?t=Icons" id="home-devices-img">
							<p class="home-callout-description"><?php echo esc_html( $callout_one_text ); ?></p>
							<a href="<?php echo esc_url( $callout_one_link ); ?>">
								<button><?php echo esc_html( $callout_one_link_text ); ?></button>
							</a>
						</div>
					</div>

					<div class="col-md-6">
						<div class="home-callout-content">
							<img src="https://api.rlje.net/acorn/artwork/size/signup-icon?t=Icons" id="home-trial-img">
							<p class="home-callout-description"><?php echo esc_html( $callout_two_text ); ?></p>
							<a href="<?php echo esc_url( $callout_two_link ); ?>">
								<button><?php echo esc_html( $callout_two_link_text ); ?></button>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
			<?php
			$html = ob_get_clean();
			echo $html;
		endif;
	}
}

$rlje_index_page = new RLJE_Index_Page();
