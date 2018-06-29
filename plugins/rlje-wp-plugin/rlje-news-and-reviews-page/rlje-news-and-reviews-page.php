<?php
/*
 * News & Reviews Section - WP Administrator
 */

class RLJE_News_And_Reviews {

	private $prefix = 'acorntv_';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'after_setup_theme', array( $this, 'news_and_reviews_init_setup' ) );
		add_action( 'admin_init', array( $this, 'create_settings' ) );
		add_action( 'admin_menu', array( $this, 'register_news_and_reviews_page' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_newsAndReviews' === $hook || 'news-reviews_page_reviewsLogoSettings' === $hook ) {
			// wp_enqueue_style( 'jquery-ui-theme-css', plugin_url( '/lib/jquery/ui/jquery-ui.theme.min.css', __FILE__ ) );
			// wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.min.js' );
			wp_enqueue_script( array( 'jquery-ui-core', 'jquery-ui-draggable' ) );
			wp_enqueue_media();
		}

		if ( 'toplevel_page_newsAndReviews' === $hook ) {
			// Versioning for cachebuster.
			$js_version  = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/news-and-reviews.js' ) );
			$css_verion = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/news-and-reviews.css' ) );
			wp_enqueue_style( 'rlje-news-and-reviews', plugins_url( 'css/news-and-reviews.css', __FILE__ ), array(), $css_verion );
			wp_enqueue_script( 'rlje-news-and-reviews', plugins_url( 'js/news-and-reviews.js', __FILE__ ), array( 'jquery-ui-core' ), $js_version, true );
		}

		if ( 'news-reviews_page_reviewsLogoSettings' === $hook ) {
			// Versioning for cachebuster.
			$js_version  = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/reviews-logo-setting.js' ) );
			$css_verion = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/reviews-logo-setting.css' ) );
			wp_enqueue_style( 'reviews-logo-setting', plugins_url( 'css/reviews-logo-setting.css', __FILE__ ), array(), $css_verion );
			wp_enqueue_script( 'reviews-logo-setting', plugins_url( 'js/reviews-logo-setting.js', __FILE__ ), array( 'jquery-ui-core' ), $js_version, true );
		}
	}

	/*
	 * Checks if the options exist else set marketing_placeholder and latest_news with default data.
	 */
	public function news_and_reviews_init_setup() {
		if ( empty( get_option( 'acorntv_marketing_placeholder' ) ) ) {
			// Set default values to MarketingPlaceholder
			$marketing_placeholder = array(
				array(
					'type' => 'image',
					'franchiseId' => 'jackirish',
					'src' => 'http://atv3.us/wp-content/uploads/homepage-ad.png',
				),
				array(
					'type' => 'video',
					'franchiseId' => '',
					'src' => '4328731797001',
				),
				array(
					'type' => 'image',
					'franchiseId' => '',
					'src' => '',
				),
				array(
					'type' => 'image',
					'franchiseId' => '',
					'src' => '',
				),
				array(
					'type' => 'image',
					'franchiseId' => '',
					'src' => '',
				)
			);

			add_option('acorntv_marketing_placeholder', $marketing_placeholder);
		}

		if ( empty( get_option( 'acorntv_latest_news' ) ) ) {
			//Set default values to LatestNews
			$news_options = array(
				array(
					'title' => 'Variety reviews British Miniseries ‘New Worlds’',
					'image' => 'http://atv3.us/wp-content/uploads/variety.png',
					'link' => 'http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/',
				),
				array(
					'title' => 'Wall Street Journal  ‘Serangoon Road’ Review',
					'image' => 'http://atv3.us/wp-content/uploads/wsj_v4.png',
					'link' => 'http://www.wsj.com/articles/tv-review-serangoon-roadsleuthing-in-singapore-1418351931',
				),
				array(
					'title' => 'NY Daily News 4 Star ‘Jamaica Inn’ Review',
					'image' => 'http://atv3.us/wp-content/uploads/dailynews_v1.png',
					'link' => 'http://www.nydailynews.com/entertainment/tv/review-jamaica-inn-article-1.2148676',
				),
				array(
					'title' => 'Variety reviews British Miniseries ‘New Worlds’',
					'image' => 'http://atv3.us/wp-content/uploads/variety.png',
					'link' => 'http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/',
				),
			);
			add_option( 'acorntv_latest_news', $news_options );
		}

		if ( empty( get_option( 'acorntv_news_options' ) ) ) {
			// Set default values to NewsOptions
			$news_options = array(
				array(
					'title' => 'Variety',
					'image' => 'http://atv3.us/wp-content/uploads/variety.png',
				),
				array(
					'title' => 'Wall Street Journal',
					'image' => 'http://atv3.us/wp-content/uploads/wsj_v4.png',
				),
				array(
					'title' => 'NY Daily News',
					'image' => 'http://atv3.us/wp-content/uploads/dailynews_v1.png',
				),
			);
			add_option( 'acorntv_news_options', $news_options );
		}
	}

	/**
	 * Register custom settings options in the admin.
	 */
	public function create_settings() {

		register_setting(
			$this->prefix . 'news_and_reviews',
			$this->prefix . 'marketing_placeholder',
			null
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

		$news_opts = get_option( $this->prefix . 'news_options' );

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
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_marketing_placeholder_field( $key_param ) {
		$key = absint( $key_param[0] );
		$opt_name = $this->prefix . 'marketing_placeholder';
		$opt_value = get_option( $opt_name );
		$value = ( ! empty( $opt_value[ $key ]['src'] ) ) ? esc_attr( $opt_value[ $key ]['src'] ) : '';
		$franchise_id = ( ! empty( $opt_value[ $key ]['franchiseId'] ) ) ? esc_attr( $opt_value[ $key ]['franchiseId'] ) : '';
		$external_link = ( ! empty( $opt_value[ $key ]['externalLink'] ) ) ? esc_attr( $opt_value[ $key ]['externalLink'] ) : '';

		$display_none = 'style="display:none"';
		$default_image_src_size = 70;
		$default_ext_image_src_size = 45;
		$default_video_src_size = 20;
		$src_size = $default_image_src_size;
		$default_image_src_placeholder = 'http://[image-url]';
		$default_video_src_placeholder = 'ID Number';
		$src_placeholder = $default_image_src_placeholder;
		if ( ! empty( $opt_value[ $key ]['type'] ) ) {
			$type = $opt_value[ $key ]['type'];
			switch ( $type ) {
				case 'video':
					$src_placeholder = $default_video_src_placeholder;
					break;
				case 'extImage':
					$src_size = $default_ext_image_src_size;
					break;
			}
		}
		?>
		<select class="mkgType" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][type]" style="vertical-align:top">
			<option value="image" <?php echo selected( $type, 'images' ); ?>>Franchise ID & Image</option>
			<option value="extImage" <?php echo selected( $type, 'extImages' ); ?>>External Link & Image</option>
			<option value="video" <?php echo selected( $type, 'video' ); ?>>Trailer ID</option>
		</select>
		<input class="mkgFranchiseId" <?php echo ( empty( $image_selected ) ) ? $display_none : ''; ?> name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][franchiseId]" type="text" size="20" placeholder="Franchise ID" value="<?php echo esc_attr( $franchise_id ); ?>"/>
		<input class="mkgExternalLink" <?php echo ( empty( $ext_image_selected ) ) ? $display_none : ''; ?> name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][externalLink]" type="text" size="45" placeholder="http://[external-link]" value="<?php echo esc_url( $external_link ); ?>"/>
		<input class="mkgSrc uploadImage" name="acorntv_marketing_placeholder[<?php echo esc_attr( $key ); ?>][src]" type="text" size="<?php echo esc_attr( $src_size ); ?>" placeholder="http://[image-url]" value="<?php echo esc_attr( $value ); ?>"
			data-videoSrcSize="<?php echo $default_video_src_size; ?>"
			data-imageSrcSize="<?php echo $default_image_src_size; ?>"
			data-extImageSrcSize="<?php echo $default_ext_image_src_size; ?>"
			data-imagePlaceholder="<?php echo $default_image_src_placeholder; ?>"
			data-videoPlaceholder="<?php echo $default_video_src_placeholder; ?>"
		/>
		<button class="uploadBtn" <?php echo ( 'video' !== $type ) ? $display_none : ''; ?>>Upload Image</button>
		<?php
	}

	/**
	 * Latest News field template.
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_latest_news_fields( $key_param ) {
		$key = absint( $key_param[0] );
		$opt_name = $this->prefix . 'latest_news';
		$opt_value = get_option( $opt_name );
		$news_options = get_option( $this->prefix . 'news_options' );
		$value_title = ( ! empty( $opt_value[ $key ]['title'] ) ) ? esc_attr( $opt_value[ $key ]['title'] ) : '';
		$value_image = ( ! empty( $opt_value[ $key ]['image'] ) ) ? esc_attr( $opt_value[ $key ]['image'] ) : '';
		$value_link = ( ! empty( $opt_value[ $key ]['link'] ) ) ? esc_attr( $opt_value[ $key ]['link'] ) : '';
		?>
		<table class="latest_news_table">
			<tbody>
				<tr>
					<th scope="row">Review Logo:</th>
					<td>
						<select class="lnwsImage" name="acorntv_latest_news[<?php echo esc_attr( $key ); ?>][image]" style="vertical-align:top">
							<option value="" <?php echo ( ! empty( $value_image ) ) ? '' : 'selected'; ?>>-- Select a Review Logo --</option>
							<?php
							if ( is_array( $news_options ) && count( $news_options ) > 0 ) :
								foreach ( $news_options as $news_option ) :
									?>
							<option value="<?php echo esc_attr( $news_option['image'] ); ?>" <?php echo ( $value_image === $news_option['image'] ) ? 'selected="selected"' : '' ; ?>><?php echo esc_html( $news_option['title'] ); ?></option>
									<?php
								endforeach;
							endif;
							?>
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
					<td><input class="lnwsLink" name="acorntv_latest_news[<?php echo esc_attr( $key ); ?>][link]" type="text" size="70" placeholder="http://[external-link]" value="<?php echo esc_attr( $value_link ); ?>"></td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * News options template.
	 * @param Array $keyParam Array parameters with a key to identify each field.
	 */
	public function acorntv_news_option_fields( $key_param ) {
		$key = absint( $key_param[0] );
		$opt_name = $this->prefix . 'news_options';
		$opt_value = get_option( $opt_name );
		$title = ( ! empty( $opt_value[ $key ]['title'] ) ) ? esc_attr( $opt_value[ $key ]['title'] ) : '';
		$image = ( ! empty( $opt_value[ $key ]['image'] ) ) ? esc_attr( $opt_value[ $key ]['image'] ) : '';
		?>
		<input class="rwsOptionTitle" name="acorntv_news_options[<?php echo esc_attr( $key ); ?>][title]" type="text" size="20" placeholder="Title..." value="<?php echo esc_attr( $title ); ?>" />
		<input class="rwsOptionSrc uploadImage" name="acorntv_news_options[<?php echo esc_attr( $key ); ?>][image]" type="text" size="70" placeholder="http://..." value="<?php echo esc_attr( $image ); ?>" />
		<button class="uploadBtn">Upload Image</button>
		<?php if ( $key > 0 ) : ?>
		<button class="removeReviewOption">Remove</button>
		<?php endif;
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
}

$rlje_news_and_reviews = new RLJE_News_And_Reviews();
