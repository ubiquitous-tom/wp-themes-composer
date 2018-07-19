<?php

class RLJE_Index_Page {

	protected $categories_home;
	protected $categories_items;
	protected $spotlight_name;
	protected $browse_id_list_availables;
	protected $nonce = 'atv#contentPage@token_nonce';

	public function __construct() {
		add_action( 'init', array( $this, 'initialize_index' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_paginate', array( $this, 'ajax_carousel_pagination' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_home_featured' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_home_spotlights' ) );
		add_action( 'rlje_homepage_bottom_section_content', array( $this, 'display_callback' ) );
	}

	public function initialize_index() {
		$this->categories_home = rljeApiWP_getHomeItems( 'categories' );
		$this->categories_items = ( isset( $this->categories_home->options ) ) ? $this->categories_home->options : array();
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
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'home_url'  => home_url(),
				'image_url' => rljeApiWP_getImageUrlFromServices(''),
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
		$page = ( ! empty( $_POST['page'] ) ) ? $_POST['page'] : null;

		$data = rljeApiWP_getContentPageItems( $content, $page );
		wp_send_json_success( $data );
	}

	public function display_home_featured() {
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
					endif;

					for ( $i = 0; $i < 2 && isset( $this->categories_items[ $i ] ); $i++ ) :
						$spotlight = $this->categories_items[ $i ];
						$this->spotlight_name = ( ! empty( $spotlight->name ) ) ? $spotlight->name : '';
				?>
				<!-- <?php echo strtoupper( $this->spotlight_name ); ?> SPOTLIGHT-->
				<div class="col-md-12">
					<?php
						set_query_var('carousel-section', array(
							'title' => $this->spotlight_name,
							'categoryObj' => $spotlight,
							'showViewAllLink' => ( isset( $this->browse_id_list_availables[ $spotlight->id ] ) ),
						));
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

	public function display_home_spotlights() {
		if ( is_home() || is_front_page() ) :
			ob_start();
		?>
		<section class="home-spotlights">
			<div class="container">
				<?php
					for ( $i=2; $i < count( $this->categories_items ); $i++ ) :
					$spotlight = $this->categories_items[ $i ];
					$this->spotlight_name = ( ! empty( $spotlight->name ) ) ? $spotlight->name : '';
				?>
			<!-- <?php echo strtoupper( $this->spotlight_name ); ?> SPOTLIGHT -->
				<div class="col-md-12">
					<?php
						set_query_var('carousel-section', array(
							'title' => $this->spotlight_name,
							'categoryObj' => $spotlight,
							'showViewAllLink' => ( isset ( $this->browse_id_list_availables[ $spotlight->id ] ) ),
						));
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
			ob_start();
		?>
		<section class="home-callout">
			<div class="container">
				<div class="col-md-12 home-callout-body">
					<div class="col-md-6" id="border-carousel">
						<div class="home-callout-content">
							<img src="https://api.rlje.net/acorn/artwork/size/devices-icon?t=Icons" id="home-devices-img">
							<p class="home-callout-description">Available on Roku, Apple TV, Samsung Smart TV, iPhone, iPad, web and more. </p>
							<a href="http://www2.acorn.tv/how-to-watch/">
								<button>Learn More</button>
							</a>
						</div>
					</div>

					<div class="col-md-6" style="padding:0px 30px;padding-bottom:50px;">
						<div class="home-callout-content">
							<img src="https://api.rlje.net/acorn/artwork/size/signup-icon?t=Icons" id="home-trial-img">
							<p class="home-callout-description">Over 1,800 hours of programming, including 60 shows you won't find anywhere else. </p>
							<?php $environment = apply_filters( 'atv_get_extenal_subdomain', '' ); ?>
							<a href="<?php echo esc_url( 'https://signup' . $environment . '.acorn.tv/createaccount.html' ); ?>">
								<button>Start Your Free Trial</button>
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
