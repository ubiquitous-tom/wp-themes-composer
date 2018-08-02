<?php
/*
 * News & Reviews Section - WP Administrator
 */

class RLJE_News_And_Reviews {

	private $prefix = 'acorntv_';
	protected $acorntv_marketing_placeholder;
	protected $acorntv_latest_news;
	protected $acorntv_news_options;
	protected $brightcove = [];

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_init', array( $this, 'news_and_reviews_init_setup' ) );
		add_action( 'admin_init', array( $this, 'create_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_news_and_reviews_page' ) );
		add_action( 'rlje_homepage_middle_section_content', array( $this, 'display_news_and_reviews' ) );
	}

	public function admin_enqueue_scripts( $hook ) {
		if ( 'toplevel_page_newsAndReviews' === $hook || 'news-reviews_page_reviewsLogoSettings' === $hook ) {
			// wp_enqueue_style( 'jquery-ui-theme-css', plugin_url( '/lib/jquery/ui/jquery-ui.theme.min.css', __FILE__ ) );
			// wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.min.js' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_media();
		}

		if ( 'toplevel_page_newsAndReviews' === $hook ) {
			if ( is_ssl() ) {
				$bc_admin_js = 'https://sadmin.brightcove.com/';
			} else {
				$bc_admin_js = 'http://admin.brightcove.com/';
			}

			$this->get_brightcove_info();
			wp_enqueue_script( 'brightcove', $bc_admin_js . 'js/BrightcoveExperiences.js', array(), false, true );

			$bc_url          = '//players.brightcove.net/' . $this->brightcove['bc_account_id'] . '/' . $this->brightcove['bc_player_id'] . '_default/index.js';
			wp_enqueue_script( 'rlje-brightcove', $bc_url, array( 'brightcove' ), false, true );

			// Versioning for cachebuster.
			$news_js_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/admin-news-and-reviews.js' ) );
			$news_css_verion = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/admin-news-and-reviews.css' ) );
			wp_enqueue_style( 'rlje-admin-news-and-reviews', plugins_url( 'css/admin-news-and-reviews.css', __FILE__ ), array(), $news_css_verion );
			wp_enqueue_script( 'rlje-admin-news-and-reviews', plugins_url( 'js/admin-news-and-reviews.js', __FILE__ ), array( 'jquery-ui-core' ), $news_js_version, true );
		}

		if ( 'news-reviews_page_reviewsLogoSettings' === $hook ) {
			// Versioning for cachebuster.
			$logo_js_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/admin-reviews-logo-setting.js' ) );
			$logo_css_verion = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/admin-reviews-logo-setting.css' ) );
			wp_enqueue_style( 'rlje-admin-reviews-logo-setting', plugins_url( 'css/admin-reviews-logo-setting.css', __FILE__ ), array(), $logo_css_verion );
			wp_enqueue_script( 'rlje-admin-reviews-logo-setting', plugins_url( 'js/admin-reviews-logo-setting.js', __FILE__ ), array( 'jquery-ui-core' ), $logo_js_version, true );
		}
	}

	public function wp_enqueue_scripts() {
		if ( ! is_home() ) {
			return;
		}

		if ( is_ssl() ) {
			$bc_admin_js = 'https://sadmin.brightcove.com/';
		} else {
			$bc_admin_js = 'http://admin.brightcove.com/';
		}

		$this->get_brightcove_info();
		wp_enqueue_script( 'brightcove', $bc_admin_js . 'js/BrightcoveExperiences.js', array(), false, true );

		$bc_url          = '//players.brightcove.net/' . $this->brightcove['bc_account_id'] . '/' . $this->brightcove['bc_player_id'] . '_default/index.js';
		$news_js_version = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/news-and-reviews.js' ) );
		$news_css_verion = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/news-and-reviews.css' ) );

		wp_enqueue_script( 'rlje-brightcove', $bc_url, array( 'jquery', 'brightcove', 'main-js' ), false, true );
		wp_enqueue_style( 'rlje-news-and-reviews', plugins_url( 'css/news-and-reviews.css', __FILE__ ), array(), $news_css_verion );
		wp_enqueue_script( 'rlje-news-and-reviews', plugins_url( 'js/news-and-reviews.js', __FILE__ ), array( 'rlje-brightcove' ), $news_js_version, true );
	}

	/*
	 * Checks if the options exist else set marketing_placeholder and latest_news with default data.
	 */
	public function news_and_reviews_init_setup() {
		$this->acorntv_marketing_placeholder = get_option( 'acorntv_marketing_placeholder' );
		if ( empty( $this->acorntv_marketing_placeholder ) ) {
			// Set default values to MarketingPlaceholder
			$marketing_placeholder = array(
				array(
					'type'        => 'image',
					'franchiseId' => 'jackirish',
					'src'         => plugins_url( 'img/upgradeannual515.png', __FILE__ ),
				),
				array(
					'type'        => 'video',
					'franchiseId' => '',
					'src'         => '4328731797001',
				),
				array(
					'type'        => 'image',
					'franchiseId' => '',
					'src'         => '',
				),
				array(
					'type'        => 'image',
					'franchiseId' => '',
					'src'         => '',
				),
				array(
					'type'        => 'image',
					'franchiseId' => '',
					'src'         => '',
				),
			);

			update_option( 'acorntv_marketing_placeholder', $marketing_placeholder );
		}

		$this->acorntv_latest_news = get_option( 'acorntv_latest_news' );
		if ( empty( $this->acorntv_latest_news ) ) {
			// Set default values to LatestNews
			$news_options = array(
				array(
					'title' => 'Variety reviews British Miniseries ‘New Worlds’',
					'image' => plugins_url( 'img/variety.png', __FILE__ ),
					'link'  => 'http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/',
				),
				array(
					'title' => 'Wall Street Journal  ‘Serangoon Road’ Review',
					'image' => plugins_url( 'img/wsj_v4.png', __FILE__ ),
					'link'  => 'http://www.wsj.com/articles/tv-review-serangoon-roadsleuthing-in-singapore-1418351931',
				),
				array(
					'title' => 'NY Daily News 4 Star ‘Jamaica Inn’ Review',
					'image' => plugins_url( 'img/dailynews_v1.png', __FILE__ ),
					'link'  => 'http://www.nydailynews.com/entertainment/tv/review-jamaica-inn-article-1.2148676',
				),
				array(
					'title' => 'Variety reviews British Miniseries ‘New Worlds’',
					'image' => plugins_url( 'img/variety.png', __FILE__ ),
					'link'  => 'http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/',
				),
			);
			update_option( 'acorntv_latest_news', $news_options );
		}

		$this->acorntv_news_options = get_option( 'acorntv_news_options' );
		if ( empty( $this->acorntv_news_options ) ) {
			// Set default values to NewsOptions
			$news_options = array(
				array(
					'title' => 'Variety',
					'image' => plugins_url( 'img/variety.png', __FILE__ ),
				),
				array(
					'title' => 'Wall Street Journal',
					'image' => plugins_url( 'img/wsj_v4.png', __FILE__ ),
				),
				array(
					'title' => 'NY Daily News',
					'image' => plugins_url( 'img/dailynews_v1.png', __FILE__ ),
				),
			);
			update_option( 'acorntv_news_options', $news_options );
		}
	}

	/**
	 * Register custom settings options in the admin.
	 */
	public function create_settings() {

		register_setting(
			$this->prefix . 'news_and_reviews',
			$this->prefix . 'marketing_placeholder',
			null //array( 'sanitize_callback' => array( $this, 'marketing_placeholder_sanitize_callback'), )
		);
		register_setting(
			$this->prefix . 'news_and_reviews',
			$this->prefix . 'latest_news',
			null
		);

		add_settings_section(
			$this->prefix . 'marketing_placeholder_options',
			'News',
			function() {
				print '<p>Load the News images (marketing) or video trailers to show in homepage.</p>';
			},
			'newsAndReviews'
		);
		add_settings_section(
			$this->prefix . 'latest_news_options',
			'Reviews',
			function() {
				print '<p>Load the Reviews with logo, title and link to show in homepage.</p>';
			},
			'newsAndReviews'
		);

		$review_items = array(
			'1st',
			'2nd',
			'3rd',
			'4th',
			'5th',
		);

		for ( $i = 0; $i < 5; $i++ ) {
			add_settings_field(
				'marketing_placeholder_' . $i,
				'<span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block;vertical-align:top;"></span><span>' . $review_items[ $i ] . ' News:</span>',
				array( $this, 'acorntv_marketing_placeholder_field' ),
				'newsAndReviews',
				$this->prefix . 'marketing_placeholder_options',
				array( $i )
			);

			if ( $i < 4 ) {
				add_settings_field(
					'latest_news_fields_' . $i,
					'<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span>' . $review_items[ $i ] . ' Review:</span>',
					array( $this, 'acorntv_latest_news_fields' ),
					'newsAndReviews',
					$this->prefix . 'latest_news_options',
					array( $i )
				);
			}
		}

		register_setting(
			$this->prefix . 'reviews_logo_settings',
			$this->prefix . 'news_options',
			null
		);

		add_settings_section(
			$this->prefix . 'news_options',
			'Reviews Logo Options',
			function() {
				print '<p>Add or Edit the Reviews Logo.</p><button id="addReviewOption">Add New Review Logo</button>';
			},
			'reviewsLogoSettings'
		);

		// $news_opts = get_option( $this->prefix . 'news_options' );
		$news_opts = $this->acorntv_news_options;

		if ( is_array( $news_opts ) ) {
			foreach ( $news_opts as $key => $opt ) {
				add_settings_field(
					'news_option_fields_' . $key,
					'Review Logo <br/>Title and Image:',
					array( $this, 'acorntv_news_option_fields' ),
					'reviewsLogoSettings',
					$this->prefix . 'news_options',
					array( $key )
				);
			}
		}
	}

	/**
	 * Marketing placeholder field template.
	 *
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_marketing_placeholder_field( $key_param ) {
		$key           = absint( $key_param[0] );
		$opt_name      = $this->prefix . 'marketing_placeholder';
		$opt_value     = $this->$opt_name; // get_option( $opt_name );
		$value         = ( ! empty( $opt_value[ $key ]['src'] ) ) ? esc_attr( $opt_value[ $key ]['src'] ) : '';
		$franchise_id  = ( ! empty( $opt_value[ $key ]['franchiseId'] ) ) ? esc_attr( $opt_value[ $key ]['franchiseId'] ) : '';
		$external_link = ( ! empty( $opt_value[ $key ]['externalLink'] ) ) ? esc_attr( $opt_value[ $key ]['externalLink'] ) : '';

		$default_image_src_size        = 70;
		$default_ext_image_src_size    = 45;
		$default_video_src_size        = 20;
		$src_size                      = $default_image_src_size;
		$default_image_src_placeholder = 'http://[image-url]';
		$default_video_src_placeholder = 'ID Number';
		$src_placeholder               = $default_image_src_placeholder;
		if ( ! empty( $opt_value[ $key ]['type'] ) ) {
			$type = $opt_value[ $key ]['type'];
			switch ( $type ) {
				case 'video':
					$src_placeholder = $default_video_src_placeholder;
					$type_text = 'Video Embed ID';
					break;
				case 'extImage':
					$src_size = $default_ext_image_src_size;
					$type_text = 'Image URL';
					break;
				case 'image':
				default:
					$type_text = 'Image URL';
			}
		}
		?>
		<div id="news-carousel-<?php echo esc_attr( $key + 1 ); ?>" class="news-carousel" data-id="<?php echo esc_attr( $key + 1 ); ?>">
			<p>
				<label for="mkgType">Carousel Type</label>
				<select class="mkgType regular-text" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][type]">
					<option value="image" <?php echo selected( $type, 'image' ); ?>>Franchise ID & Image</option>
					<option value="extImage" <?php echo selected( $type, 'extImage' ); ?>>External Link & Image</option>
					<option value="video" <?php echo selected( $type, 'video' ); ?>>Trailer ID</option>
				</select>
			</p>

			<p class="mkgFranchiseId">
				<label for="mkgFranchiseId">Franchise ID</label>
				<input id="mkgFranchiseId" class="regular-text" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][franchiseId]" type="text" size="20" placeholder="Franchise ID" value="<?php echo esc_attr( $franchise_id ); ?>">
				<span class="help">(e.g. <?php echo esc_url( trailingslashit( home_url( $franchise_id ) ) ); ?>)</span>
			</p>


			<p class="mkgExternalLink">
				<label for="mkgExternalLink">External URL Link</label>
				<input id="mkgExternalLink" class="regular-text" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][externalLink]" type="text" size="45" placeholder="http://[external-link]" value="<?php echo esc_url( $external_link ); ?>">
			</p>

			<p class="mkgSrc">
				<label for="uploadImage"><?php echo esc_html( $type_text ); ?></label>
				<input id="uploadImage" class="uploadImage regular-text" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][src]" type="text" size="<?php echo esc_attr( $src_size ); ?>" placeholder="http://[image-url]" value="<?php echo esc_attr( $value ); ?>"
					data-videoSrcSize="<?php echo esc_attr( $default_video_src_size ); ?>"
					data-imageSrcSize="<?php echo esc_attr( $default_image_src_size ); ?>"
					data-extImageSrcSize="<?php echo esc_attr( $default_ext_image_src_size ); ?>"
					data-imagePlaceholder="<?php echo esc_attr( $default_image_src_placeholder ); ?>"
					data-videoPlaceholder="<?php echo esc_attr( $default_video_src_placeholder ); ?>">
				<!-- Can't upload image here because of how we set up our infrastructure -->
				<!-- <button class="uploadBtn">Upload Image</button> -->
			</p>

			<?php if ( ! empty( $value ) ) : ?>
			<p>
				<label for="preview">Preview</label>
				<?php if ( 'video' ===  $type ) : ?>
				<div class="video-wrapper">
					<div class="video-preview">
						<video preload
							id="brightcove-trailer-player"
							data-account="<?php echo esc_attr( $this->brightcove['bc_account_id'] ); ?>"
							data-player="<?php echo esc_attr( $this->brightcove['bc_player_id'] ); ?>"
							data-embed="default"
							data-video-id="ref:<?php echo esc_attr( $value ); ?>"

							class="video-js embed-responsive embed-responsive-16by9"
							controls></video>
					</div>
				</div>
				<?php else : ?>
				<img class="image-preview" src="<?php echo esc_attr( $value ); ?>" alt="Preview Image">
				<?php endif; ?>
			</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Latest News field template.
	 *
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_latest_news_fields( $key_param ) {
		$key          = absint( $key_param[0] );
		$opt_name     = $this->prefix . 'latest_news';
		$opt_value    = get_option( $opt_name );
		$news_options = get_option( $this->prefix . 'news_options' );
		$value_title  = ( ! empty( $opt_value[ $key ]['title'] ) ) ? esc_attr( $opt_value[ $key ]['title'] ) : '';
		$value_image  = ( ! empty( $opt_value[ $key ]['image'] ) ) ? esc_attr( $opt_value[ $key ]['image'] ) : '';
		$value_link   = ( ! empty( $opt_value[ $key ]['link'] ) ) ? esc_attr( $opt_value[ $key ]['link'] ) : '';
		?>
		<table class="latest_news_table">
			<tbody>
				<tr>
					<th scope="row">Review Logo:</th>
					<td>
						<select class="lnwsImage" name="acorntv_latest_news[<?php echo esc_attr( $key ); ?>][image]" style="vertical-align:top">
							<option value="">-- Select a Review Logo --</option>
							<?php if ( is_array( $news_options ) && count( $news_options ) > 0 ) : ?>
							<?php foreach ( $news_options as $news_option ) : ?>
							<option value="<?php echo esc_attr( $news_option['image'] ); ?>" <?php selected( $value_image, $news_option['image'] ); ?>><?php echo esc_html( $news_option['title'] ); ?></option>
							<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">Review Title:</th>
					<td>
						<input class="lnwsText" name="acorntv_latest_news[<?php echo esc_attr( $key ); ?>][title]" type="text" size="70" placeholder="Title..." value="<?php echo esc_attr( $value_title ); ?>">
					</td>
				</tr>
				<tr>
					<th scope="row">Review Link:</th>
					<td>
						<input class="lnwsLink" name="acorntv_latest_news[<?php echo esc_attr( $key ); ?>][link]" type="text" size="70" placeholder="http://[external-link]" value="<?php echo esc_attr( $value_link ); ?>">
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * News options template.
	 *
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_news_option_fields( $key_param ) {
		$key       = absint( $key_param[0] );
		$opt_name  = $this->prefix . 'news_options';
		$opt_value = get_option( $opt_name );
		$title     = ( ! empty( $opt_value[ $key ]['title'] ) ) ? esc_attr( $opt_value[ $key ]['title'] ) : '';
		$image     = ( ! empty( $opt_value[ $key ]['image'] ) ) ? esc_attr( $opt_value[ $key ]['image'] ) : '';
		?>
		<input class="rwsOptionTitle" name="acorntv_news_options[<?php echo esc_attr( $key ); ?>][title]" type="text" size="20" placeholder="Title..." value="<?php echo esc_attr( $title ); ?>" />
		<input class="rwsOptionSrc uploadImage" name="acorntv_news_options[<?php echo esc_attr( $key ); ?>][image]" type="text" size="70" placeholder="http://..." value="<?php echo esc_attr( $image ); ?>" />
		<button class="uploadBtn">Upload Image</button>
		<?php if ( $key > 0 ) : ?>
		<button class="removeReviewOption">Remove</button>
			<?php
		endif;
	}

	public function marketing_placeholder_sanitize_callback( $data ) {
		// Detect multiple sanitizing passes.
		// Accomodates bug: https://core.trac.wordpress.org/ticket/21989
		static $pass_count = 0;
		$pass_count++;

		if ( $pass_count <= 1 ) {
			// Handle any single-time / performane sensitive actions.
			$new_data = [];
			foreach ( $data as $carousel ) {
				if ( ! empty( $carousel['src'] ) ) {
					$new_data[] = $carousel;
				}
			}
			update_option( 'acorntv_marketing_placeholder', $new_data );
		}

		$country_code = ( ! empty( rljeApiWP_getCountryCode() ) ) ? rljeApiWP_getCountryCode() : 'US';
		$transient_key = 'rlje_news_and_review_' . $country_code;
		delete_transient( $transient_key );
	}

	/**
	 * Register news and review page in admin menu page.
	 */
	public function register_news_and_reviews_page() {
		add_menu_page(
			'News & Reviews',
			'News & Reviews',
			'manage_options',
			'newsAndReviews',
			array( $this, 'acorntv_news_and_reviews_page' ),
			'dashicons-feedback',
			6
		);

		add_submenu_page(
			'newsAndReviews',
			'Reviews Logo Options',
			'Reviews Logo Settings',
			'manage_options',
			'reviewsLogoSettings',
			array( $this, 'acorntv_reviews_logo_settings_page' )
		);
	}

	/**
	 * Admin News and Review page template.
	 */
	public function acorntv_news_and_reviews_page() {
		?>
		<div class="wrap" id="news_reviews">
			<h1>News & Reviews</h1>
			<form method="post" action="options.php">
		<?php

		settings_fields( $this->prefix . 'news_and_reviews' );

		do_settings_sections( 'newsAndReviews' );

		submit_button();

		?>
			</form>
		</div>
		<?php
	}

	/**
	 * Admin Review Logo Settings page template.
	 */
	public function acorntv_reviews_logo_settings_page() {
		?>
		<div class="wrap" id="reviews_logo_settings">
			<h1>Reviews Logo Settings</h1>
			<form method="post" action="options.php">
		<?php

			settings_fields( $this->prefix . 'reviews_logo_settings' );

			do_settings_sections( 'reviewsLogoSettings' );

			submit_button();

		?>
			</form>
			<p class="notice">
				* After to save any change, you need go to <a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=newsAndReviews' ); ?>">News and Reviews</a> and update the proper Review Logo fields then clicks on <b>Save Changes</b> to apply it.
			</p>
		</div>
		<?php
	}

	public function display_news_and_reviews() {
		if ( rljeApiWP_getCountryCode() ) {
			return;
		}

		$country_code = ( ! empty( rljeApiWP_getCountryCode() ) ) ? rljeApiWP_getCountryCode() : 'US';
		$transient_key = 'rlje_news_and_review_' . $country_code;
		$html = get_transient( $transient_key );
		if ( false === $html ) :
			ob_start();
		?>
		<section class="home-middle hidden-xs hidden-sm">
			<div class="container">
				<div class="row">
					<h4 class="subnav">News &amp; Reviews</h4>
					<!-- MARKETING PLACEHOLDER -->
					<?php // get_template_part( 'partials/homepage-section-marketing-placeholder' ); ?>
					<?php $this->display_carousel(); ?>

					<!-- LATEST NEWS -->
					<?php // get_template_part( 'partials/homepage-section-lastnews' ); ?>
					<?php $this->display_reviews(); ?>
				</div>
			</div>
		</section>
		<?php
			$html = ob_get_clean();
			set_transient( $transient_key, $html, 15 * MINUTE_IN_SECONDS );
		endif;

		echo $html;
	}

	public function display_carousel() {
		$marketing_places = $this->get_display_carousels();//get_option( 'acorntv_marketing_placeholder' );
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/carousel.php';
		$html = ob_get_clean();
		echo $html;
	}

	public function display_reviews() {
		$reviews        = get_option( 'acorntv_latest_news' );
		$reviewsSources = get_option( 'acorntv_news_options' );
		ob_start();
		require_once plugin_dir_path( __FILE__ ) . 'templates/reviews.php';
		$html = ob_get_clean();
		echo $html;
	}

	protected function get_display_carousels() {
		$display_carousels = [];
		$carousels = get_option( 'acorntv_marketing_placeholder' );
		foreach ( $carousels as $carousel ) {
			if ( ! empty( $carousel['src'] ) ) {
				$display_carousels[] = $carousel;
			}
		}

		return $display_carousels;
	}

	protected function get_brightcove_info() {
		// We need all these brightcove stuff because homepage uses share account only.
		$brightcove_settings = get_option( 'rlje_theme_brightcove_shared_settings' );

		if ( ! empty( $brightcove_settings ) ) {
			$this->brightcove['bc_account_id'] = $brightcove_settings['shared_account_id'];
			$this->brightcove['bc_player_id']  = $brightcove_settings['shared_player_id'];
		}
	}
}

$rlje_news_and_reviews = new RLJE_News_And_Reviews();
