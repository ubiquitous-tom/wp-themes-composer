<?php



if ( ! function_exists( 'acorntv_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 */
	function acorntv_setup() {
		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		*/
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'   => __( 'Top primary menu', 'acorntv' ),
			'secondary' => __( 'Footer secondary menu', 'acorntv' ),
		) );

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		* Enable support for Post Formats.
		*
		* See: https://codex.wordpress.org/Post_Formats
		*/
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
			'chat',
		) );

	}
endif;

add_action( 'after_setup_theme', 'acorntv_setup' );

add_action( 'wp_enqueue_scripts', 'acorntv_hook_css_js', 0 );
function acorntv_hook_css_js() {
	// Enqueue css.
	// wp_enqueue_style( 'normalize_css', get_template_directory_uri() . '/lib/normalize/normalize.min.css', array(), '3.0.2' );
	wp_enqueue_style( 'normalize_css', '//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css', array(), '8.0.0' );
	wp_enqueue_style( 'jquery_ui_css', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.min.css', array(), '1.11.4' );
	// wp_enqueue_style( 'jquery_ui_css', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css', array(), '1.11.4' );
	wp_enqueue_style( 'fontawesome_css', get_template_directory_uri() . '/genericons/font-awesome.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'bootstrap_css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '3.3.7' );
	wp_enqueue_style( 'fancybox_css', get_template_directory_uri() . '/lib/fancybox/jquery.fancybox.css', array(), '1.0.0' );
	// wp_enqueue_style( 'smartbanner_css', get_template_directory_uri() . '/lib/smartbanner/jquery.smartbanner.css', array(), '1.0.0' );
	// wp_enqueue_style( 'smartbanner_css', '//cdnjs.cloudflare.com/ajax/libs/jquery.smartbanner/1.0.0/jquery.smartbanner.min.css', array( 'jquery_ui_css' ), '1.0.0' );
	wp_enqueue_style( 'main_style_css', get_template_directory_uri() . '/css/main-style.css', array(), '1.4.8' );

	// Enqueue scripts.
	// wp_enqueue_script( 'cookies-js', get_template_directory_uri() . '/lib/cookies/cookies.js', array(), null, true );
	wp_enqueue_script( 'cookies-js', '//cdn.jsdelivr.net/npm/doc-cookies@1.1.0/cookies.min.js', array(), '1.1.0', true );
	// wp_enqueue_script( 'jquery-min-js', get_template_directory_uri() . '/lib/jquery/jquery.min.js', array(), null, true );
	// wp_enqueue_script( 'jquery-ui-min-js', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.min.js', array(), null, true );
	wp_enqueue_script( 'jquery-ui-core' );
	// wp_enqueue_script( 'jquery-unveil-js', get_template_directory_uri() . '/lib/jquery/jquery-unveil/jquery.unveil.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'jquery-unveil-js', '//cdnjs.cloudflare.com/ajax/libs/unveil/1.3.0/jquery.unveil.min.js', array( 'jquery' ), '1.3.0', true );
	// wp_enqueue_script( 'jquery-smartbanner-js', get_template_directory_uri() . '/lib/smartbanner/jquery.smartbanner.js', array( 'jquery-ui-core' ), null, true );
	// wp_enqueue_script( 'jquery-smartbanner-js', '//cdnjs.cloudflare.com/ajax/libs/jquery.smartbanner/1.0.0/jquery.smartbanner.min.js', array( 'jquery-ui-core' ), '1.0.0', true );
	// wp_enqueue_script( 'modernizr-js', get_template_directory_uri() . '/lib/modernizr/modernizr.min.js', array(), null, true );
	wp_enqueue_script( 'modernizr-js', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js', array(), '2.8.3', true );
	wp_enqueue_script( 'bootstrap-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
	wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/lib/fancybox/jquery.fancybox.pack.js', array( 'jquery' ), '2.1.5', true );
	// wp_enqueue_script( 'brightcove', get_template_directory_uri() . '/lib/brightcove/BrightcoveExperiences.js', array(), null, true );
	wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.4.3', true );

	// if ( get_query_var( 'pagecustom' ) === 'browse' ) {
	// 	wp_enqueue_script( 'orderby-js', get_template_directory_uri() . '/js/orderby.js', array( 'jquery' ), '1.1.1', true );
	// }

	// if ( get_query_var( 'pagecustom' ) === 'episode' ) {
	// 	wp_localize_script('main-js', 'atv_player_object', array(
	// 		'ajax_url' => home_url( 'ajax_atv' ),
	// 		'token' => wp_create_nonce( 'atv#episodePlayer@token_nonce' )
	// 	));
	// }

	// if ( is_home() ) {
	// 	wp_enqueue_script( 'carousel-pagination-js', get_template_directory_uri() . '/js/carouselPagination.js', array( 'jquery' ), '1.1.2', true );
	// 	// Add javascript global variables.
	// 	$atv_global = array(
	// 		'ajax_url'  => home_url( 'ajax_atv' ),
	// 		'home_url'  => home_url(),
	// 		'image_url' => rljeApiWP_getImageUrlFromServices(''),
	// 		'token'     => wp_create_nonce( 'atv#contentPage@token_nonce' ),
	// 	);
	// 	wp_localize_script( 'carousel-pagination-js', 'atv_object', $atv_global );
	// }
}

add_action( 'init', 'acorntv_rewrites_urls' );
function acorntv_rewrites_urls() {
	// Remember add or remove the rule in acorntv_check_rewrite_rules function too.
	// add_rewrite_rule( 'wp\-.+$', 'index.php', 'top' );
	// add_rewrite_rule( '^content/.+$', 'index.php', 'top' );
	// add_rewrite_rule( 'ios\-contact$', 'index.php?pagecustom=ios-support', 'top' );
	// add_rewrite_rule( '^ajax_atv$', 'index.php?pagecustom=ajax', 'top' );
	// add_rewrite_rule( 'browse$', 'index.php?pagecustom=browse', 'top' );
	// add_rewrite_rule( 'browse/(.+)$', 'index.php?pagecustom=browse&section=$matches[1]', 'top' );
	// add_rewrite_rule( 'schedule$', 'index.php?pagecustom=schedule&section=featured', 'top' );
	// add_rewrite_rule( 'schedule/comingsoon$', 'index.php?pagecustom=schedule&section=comingsoon', 'top' );
	// add_rewrite_rule( 'schedule/leavingsoon$', 'index.php?pagecustom=schedule&section=leavingsoon', 'top' );
	// add_rewrite_rule( 'futuredate(\/today)*$', 'index.php?pagecustom=futuredate&section=today', 'top' );
	// add_rewrite_rule( 'futuredate/([\d]{8})$', 'index.php?pagecustom=futuredate&section=$matches[1]', 'top' );
	// add_rewrite_rule( 'country(\/clear)*$', 'index.php?pagecustom=countryfilter&section=clear', 'top' );
	// add_rewrite_rule( 'country/([\w]{2})$', 'index.php?pagecustom=countryfilter&section=$matches[1]', 'top' );
	// add_rewrite_rule( 'videodebugger/on$', 'index.php?pagecustom=videodebugger&section=on', 'top' );
	// add_rewrite_rule( 'videodebugger/off$', 'index.php?pagecustom=videodebugger&section=off', 'top' );
	// add_rewrite_rule( 'videodebugger$', 'index.php?pagecustom=videodebugger&section=off', 'top' );
	// add_rewrite_rule( 'streamposition$', 'index.php?pagecustom=streamposition', 'top' );
	// add_rewrite_rule( 'search/(.+)', 'index.php?pagecustom=search&search_text=$matches[1]', 'top' );
	add_rewrite_rule( 'contactus$', 'index.php?pagecustom=contact', 'top' );
	// add_rewrite_rule( 'signupnewsletter$', 'index.php?pagecustom=signupnewsletter', 'top' );
	// add_rewrite_rule( '(.+)/(.+)/(.+)$', 'index.php?pagecustom=episode&franchise_id=$matches[1]&season_name=$matches[2]&episode_name=$matches[3]', 'top' );
	// add_rewrite_rule( '(.+)/trailer$', 'index.php?pagecustom=trailer&franchise_id=$matches[1]', 'top' );
	// add_rewrite_rule( '^(?!landing\/|page\/)(.+)/(.+)$', 'index.php?pagecustom=season&franchise_id=$matches[1]&season_name=$matches[2]', 'top' );
	// add_rewrite_rule( '^(?!landing\/|page\/)(.+)$', 'index.php?pagecustom=franchise&franchise_id=$matches[1]', 'top' );
	//
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	// Turn off oEmbed auto discovery.
	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	// Remove a redirect that adds a slash at the end of the url if it hasn't.
	// remove_filter( 'template_redirect', 'redirect_canonical' );
	// Remove wp version from head.
	remove_action( 'wp_head', 'wp_generator' );
	// Remove xmlrpc link from head.
	remove_action( 'wp_head', 'rsd_link' );
	// Remove wlwmanifest link from head.
	remove_action( 'wp_head', 'wlwmanifest_link' );

}

add_filter( 'the_generator', 'acorntv_remove_wp_version' );
function acorntv_remove_wp_version() {
	return '';
}

add_filter( 'query_vars', 'acorntv_wp_query_vars' );
function acorntv_wp_query_vars( $query_vars ) {
	$query_vars[] = 'pagecustom';
	$query_vars[] = 'franchise_id';
	$query_vars[] = 'season_name';
	$query_vars[] = 'episode_name';
	$query_vars[] = 'search_text';
	$query_vars[] = 'section';
	return $query_vars;
}

add_action( 'switch_theme', 'acorntv_deactivation_function' );
function acorntv_deactivation_function () {
	flush_rewrite_rules( false );
}

add_action( 'generate_rewrite_rules', 'acorntv_check_rewrite_rules');
function acorntv_check_rewrite_rules( $wp_rewrite ) {
	// This hook is execute when goes to Settings > Permalinks and after to active the theme.
	// To flush and add the rules is necessary to press save button in Settings > Permalinks .
	// To check and add new rules is not necessary to press save button in Settings > Permalinks.
	$acorntv_rewrite_rules = array(
		// 'wp\-.+$' => 'index.php',
		// '^content/.+$' => 'index.php',
		// 'ios\-contact$' => 'index.php?pagecustom=ios-support',
		// '^ajax_atv$' => 'index.php?pagecustom=ajax',
		// 'browse$' => 'index.php?pagecustom=browse',
		// 'browse/(.+)$' => 'index.php?pagecustom=browse&section=$matches[1]',
		// 'schedule$' => 'index.php?pagecustom=schedule&section=featured',
		// 'schedule/comingsoon$' => 'index.php?pagecustom=schedule&section=comingsoon',
		// 'schedule/leavingsoon$' => 'index.php?pagecustom=schedule&section=leavingsoon',
		// 'futuredate(\/today)*$' => 'index.php?pagecustom=futuredate&section=today',
		// 'futuredate/([\d]{8})$' => 'index.php?pagecustom=futuredate&section=$matches[1]',
		// 'country(\/clear)*$' => 'index.php?pagecustom=countryfilter&section=clear',
		// 'videodebugger/on$' => 'index.php?pagecustom=videodebugger&section=on',
		// 'videodebugger/off$' => 'index.php?pagecustom=videodebugger&section=off',
		// 'videodebugger$' => 'index.php?pagecustom=videodebugger&section=off',
		// 'country/([\w]{2})$' => 'index.php?pagecustom=countryfilter&section=$matches[1]',
		// 'streamposition$' => 'index.php?pagecustom=streamposition',
		// 'search/(.+)' => 'index.php?pagecustom=search&search_text=$matches[1]',
		'contactus$' => 'index.php?pagecustom=contact',
		// 'signupnewsletter$' => 'index.php?pagecustom=signupnewsletter',
		// '(.+)/(.+)/(.+)$' => 'index.php?pagecustom=episode&franchise_id=$matches[1]&season_name=$matches[2]&episode_name=$matches[3]',
		// '(.+)/trailer$' => 'index.php?pagecustom=trailer&franchise_id=$matches[1]',
		// '^(?!landing\/|page\/)(.+)/(.+)$' => 'index.php?pagecustom=season&franchise_id=$matches[1]&season_name=$matches[2]',
		// '^(?!landing\/|page\/)(.+)$' => 'index.php?pagecustom=franchise&franchise_id=$matches[1]',
	);
	// Checks if it exists each rewrite rules.
	foreach ( $acorntv_rewrite_rules as $key => $rule) {
		if ( ! isset( $wp_rewrite->rules[ $key ] ) ) {
			$wp_rewrite->rules = array( $key => $rule ) + $wp_rewrite->rules;
		}
	}
}

add_filter( 'template_include', 'acorntv_loading_template', 1, 1 );
function acorntv_loading_template( $template ) {
	$page_name = get_query_var( 'pagecustom' );
	$have_content_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'home' );
	$template_name = ( ! $have_content_available && $page_name != 'countryfilter' ) ? 'noContentAvailable' : $page_name;
	$is_page = get_post();
	if ( ! empty( $template_name ) ) {
		$path = '/templates/' . $template_name . '.php';
		if ( 'ajax' === $page_name ) {
			$path = '/ajax/ajax.php';
		}
		return dirname( __FILE__ ) . $path;
	}

	return $template;
}

/**
 * Remove admin menu options don't used.
 */
// add_action( 'admin_menu', 'remove_admin_menus' );
function remove_admin_menus() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'users.php' );
}

/**
 * Remove admin menu bar on mobile view.
 */
// add_filter( 'show_admin_bar', '__return_false' );

/**
 * Custom permalink to page content type.
 */
// add_action( 'init', 'acorntv_custom_page_permalink' );
function acorntv_custom_page_permalink() {
	global $wp_rewrite;
	// Change Page Structure to use /page in the url.
	$wp_rewrite->page_structure = $wp_rewrite->root . 'page/%pagename%';
}

/**
 * This Filter gets the subdomain to use it in the exteral urls adding .dev or .qa
 */
add_filter( 'atv_get_extenal_subdomain', 'acorntv_get_extenal_subdomain' );
function acorntv_get_extenal_subdomain( $extenal_subdomain ) {
	$extenal_subdomain = '';
	$environments = array( 'dev', 'qa' );

	foreach ( $environments as $environment ) {
		if ( strpos( RLJE_BASE_URL, $environment ) !== false ) {
			$extenal_subdomain = '-' . $environment;
			break;
		}
	}

	return $extenal_subdomain;
}

/**
 * This Filter gets the image url in accordance with the environent (prod, dev or qa).
 */
add_filter( 'atv_get_image_url', 'acorntv_get_image_url' );
function acorntv_get_image_url( $image ) {
	$base_img_url = RLJE_BASE_URL . '/artwork/size/' . $image;
	return $base_img_url;
}

/**
 * This Filter checks if the total of episodes in a franchise is less than 4 episodes.
 */
add_filter( 'atv_is_less_than_4_episodes', 'acorntv_is_less_than_4_episodes' );
function acorntv_is_less_than_4_episodes( $seasons ) {
	$isLessThan4Episodes = true;
	$totalEpisodes = 0;
	foreach ( $seasons as $season ) {
		$totalEpisodes += count( $season->episodes );
		if ( $totalEpisodes > 3 ) {
			$isLessThan4Episodes = false;
			break;
		}
	}
	return $isLessThan4Episodes;
}

/**
 * This Filter checks if the franchises are available for a country.
 */
add_filter( 'atv_haveFranchisesAvailableByCountry', 'acorntv_haveFranchisesAvailableByCountry' );
function acorntv_haveFranchisesAvailableByCountry( $page ) {
	$haveFranchisesAvailableByContry = true;
	switch ( $page ) {
		case 'franchise':
			$franchiseId = get_query_var( 'franchise_id' );
			$franchise = rljeApiWP_getFranchiseById( $franchiseId );
			if ( is_object( $franchise ) && count( $franchise->seasons ) == 0 && ! isset( $franchise->episodes ) ) {
				$haveFranchisesAvailableByContry = false;
			}
			break;
		case 'section':
			$ignoreSection = array(
				'all' => 'section',
				'recentlywatched' => 'section',
				'yourwatchlist' => 'section',
			);
			$section = get_query_var( 'section' );
			if ( ! empty( $section ) && ! isset( $ignoreSection[ $section ] ) ) {
				$section = str_replace( '-', '+', $section );
				$getBrowseSection = rljeApiWP_getItemsByCategoryOrCollection( $section );
				if ( isset( $getBrowseSection['code'] ) && $getBrowseSection['code'] == 204 ) {
					$haveFranchisesAvailableByContry = false;
				}
				break;
			}
		default:
			$getInitJson = rljeApiWP_getInitialJSONItems();
			if ( isset ( $getInitJson['code'] ) && $getInitJson['code'] == 204 ) {
				$haveFranchisesAvailableByContry = false;
			}
	}
	return $haveFranchisesAvailableByContry;
}

/**
 * This Filter checks the browser version.
 */
add_filter( 'atv_browser_detection', 'acorntv_browser_detection' );
function acorntv_browser_detection($userAgent) {
	$isUpgrageMsgShown = false;
	if(!isset($_COOKIE['dismissUpgradeMessage'])) {
	$uagent = strtolower($userAgent);
	$isMobile = preg_match("/(iphone|ipod|ipad|android|blackberry|iemobile|silk)/", $uagent);
	$browsers = array(
		'chrome' => (preg_match("/webkit/", $uagent)  && preg_match("/chrome/", $uagent) && !preg_match("/edge/", $uagent)),
		'firefox' => (preg_match("/mozilla/", $uagent) && preg_match("/firefox/", $uagent)),
		'msie' => ((preg_match("/msie/", $uagent) && !preg_match("/edge/", $uagent)) || (preg_match("/trident/", $uagent) && !preg_match("/edge/", $uagent))),
		'safari' => (preg_match("/safari/", $uagent) && preg_match("/applewebkit/", $uagent) && !preg_match("/chrome/", $uagent))
	);
	if(!$isMobile) {
		foreach($browsers as $key=>$browser) {
			if($browser) {
				$version = '';
				preg_match('/('.$key.')(\s|\/)([0-9]+)/', $uagent, $browserMatched);
				if ($browserMatched) {
					$version = $browserMatched[3];
				} else {
					if(preg_match("/version\/([0-9]+)/", $uagent)) {
						preg_match("/version\/([0-9]+)/", $uagent, $browserMatched);
					}else {
						preg_match("/rv:([0-9]+)/", $uagent, $browserMatched);
					}
					$version = $browserMatched ? $browserMatched[1] : '';
				}

				switch ($key) {
					case 'chrome':
						if('' !== $version && intval($version) <= 56) {
							$isUpgrageMsgShown = true;
						}
						break;
					case 'firefox':
						if('' !== $version && intval($version) <= 51) {
							$isUpgrageMsgShown = true;
						}
						break;
					case 'safari':
						// Safari 8
						preg_match("/version\/([0-9]+)/", $uagent, $safariVersion);
						if((isset($safariVersion[1]) && intval($safariVersion[1]) <= 8)) {
							$isUpgrageMsgShown = true;
						}
						break;
					case 'msie':
						if('' !== $version && intval($version) <= 10) {
							$isUpgrageMsgShown = true;
						}
						break;
					default:
						$isUpgrageMsgShown = false;
				}
				break;
			}

		}
	}
	}
	return $isUpgrageMsgShown;
}

/**
 * This Filter gets the Recently Watched or Watchlist by the current user.
 */
add_filter('atv_get_user_watch', 'acorntv_get_user_watch');
function acorntv_get_user_watch($type) {
	$result = array();
	if(!empty($_COOKIE['ATVSessionCookie'])) {
		$atvSessionCookie = $_COOKIE['ATVSessionCookie'];
		switch($type) {
			case 'recentlyWatched' :
				$result = rljeApiWP_getUserRecentlyWatched($atvSessionCookie);
				break;
			case 'watchlist' :
				$result = rljeApiWP_getUserWatchlist($atvSessionCookie);
				break;
		}
	}
	return $result;
}


/**
 * This Filter gets the watch spotlight.
 */
add_filter('atv_get_watch_spotlight_items', 'acorntv_get_watch_spotlight_items');
function acorntv_get_watch_spotlight_items( $watch_type ) {
	switch ( $watch_type ) {
		case 'watchlist':
			$result = apply_filters( 'atv_get_user_watch', 'watchlist' );
			set_query_var( 'carousel-section', 'Watchlist' );
			break;
		default:
			$result = apply_filters( 'atv_get_user_watch', $watch_type );
			set_query_var( 'carousel-section', 'Recently Watched' );
			if ( 1 > count( $result ) ) {
				$result = apply_filters( 'atv_get_user_watch', 'watchlist' );
				set_query_var( 'carousel-section', 'Watchlist' );
			}
	}

	return $result;
}

/**
 * This Filter gets the next episode data.
 */
add_filter('atv_get_next_episode_data', 'acorntv_get_next_episode_data', 10, 3);
function acorntv_get_next_episode_data($franchise, $seasonID, $episodeID) {
	$result = null;
	$nextEpisode = false;
	$break = false;
	foreach($franchise->seasons as $season){
		if($season->id === $seasonID || $nextEpisode) {
		foreach($season->episodes as $key=>$episode) {
			if($nextEpisode){
				$result = $episode;
				$result->seasonName = $season->name;
				$result->episodeNumber = $key+1;
				$break = true;
				break;
			}
			else if($episode->id === $episodeID) {
				$nextEpisode = true;
			}
		}
		if($break) {
			break;
		}
		}
	}
	return $result;
}

/**
 * This Filter gets a franchise by ID checking before if it is a valid ID.
 */
add_filter('atv_get_franchise_by_ID', 'acorntv_get_franchise_by_ID');
function acorntv_get_franchise_by_ID($franchiseId) {
	$return = false;
	$isValidID = apply_filters('atv_is_franchiseID_valid', $franchiseId);
	if($isValidID) {
		$return = rljeApiWP_getFranchiseById($franchiseId);
	}
	return $return;
}

/**
 * This Filter checks if a franchiseId is valid (true|false).
 */
add_filter('atv_is_franchiseID_valid', 'acorntv_is_franchiseID_valid');
function acorntv_is_franchiseID_valid($franchiseId) {
	$return = false;
	$browse = rljeApiWP_reducedInitialJSONItems('browse');
	if(is_object($browse) && isset($browse->options)) {
		foreach($browse->options as $genre) {
			$media = (isset($genre->media)) ? $genre->media: false;
			if($media && isset($media[$franchiseId])) {
				$return = true;
				break;
			}
		}
	}
	return $return;
}

/**
 * This Filter return the elements for responsive lg carousel.
 */
function acorntv_get_completed_carousel_items($array) {
	$total = count($array);
	$return = $array;
	$key = 0;
	while($total %4 != 0) {
		$return[] = $array[$key];
		$key++;
		$total = count($return);
	}
	return $return;
}
add_filter('atv_get_completed_carousel_items', 'acorntv_get_completed_carousel_items');

/**
 * This Filter return the section id for the contentPage API.
 */
function acorntv_get_contentPage_section_id($sectionId) {
	$return = '';
	if(!empty($sectionId)) {
		$return = str_replace(' ', '+', $sectionId);
	}
	return $return;
}
add_filter('atv_get_contentPage_section_id', 'acorntv_get_contentPage_section_id');

/**
 * This Filter return the section id for the contentPage API.
 */
function acorntv_convert_browseSlug_to_contentID($sectionId) {
	$return = '';
	if(!empty($sectionId)) {
		$return = str_replace('-', '+', $sectionId);
	}
	return $return;
}
add_filter('atv_convert_browseSlug_to_contentID', 'acorntv_convert_browseSlug_to_contentID');

/**
 * This Filter return the section id for browse page.
 */
function acorntv_get_browse_section_id($sectionId) {
	$return = str_replace(' ', '-', strtolower($sectionId));
	return $return;
}
add_filter('atv_get_browse_section_id', 'acorntv_get_browse_section_id');

/**
 * Get Browse genres availables.
 */
function acorntv_get_browse_genres_availables() {
	$return = array();
	$guideObj = rljeApiWP_getBrowseItems('guide');
	if(!empty($guideObj->options) && is_array($guideObj->options)) {
		$guideItems = $guideObj->options;
		foreach ($guideItems as $guide) {
			$browseId = $guide->id;
			$return[$browseId] = $guide->name;
		}
	}
	return $return;
}
add_filter('atv_get_browse_genres_availables', 'acorntv_get_browse_genres_availables');

/**
 * This Filter returns the proper link for the hero carousel item.
 */
function acorntv_heroCarusel_link($item) {
	$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
	$link = $baseUrlPath.'/'.$item->franchiseID;
	if (!empty($item->seriesName)) {
		$link = $link.'/'.rljeApiWP_convertSeasonNameToURL($item->seriesName);
	}
	return $link;
}
add_filter('atv_heroCarusel_link', 'acorntv_heroCarusel_link');

/**
 * This Filter returns the item with its proper img and href related to its id.
 */
function acorntv_add_img_and_href($item){
	if(!isset($item->href)) {
		$id = (isset($item->id)) ? $item->id: $item->franchiseID;
		$item->href = $id;
	}
	if(!isset($item->img)) {
		$img = (isset($item->image)) ? $item->image: $item->href.'_avatar';
		$item->img = rljeApiWP_getImageUrlFromServices($img);
	}
	return $item;
}
add_filter('atv_add_img_and_href', 'acorntv_add_img_and_href');

/**
 * This Filter returns the episode number if sequence value exist else its order.
 */
function acorntv_get_episode_number($episode, $order){
	$episodeNumber = $order;
	if(!empty($episode->sequence)) {
		$episodeNumber = $episode->sequence;
	}
	return $episodeNumber;
}
add_filter('atv_get_episode_number', 'acorntv_get_episode_number', 10, 2);



function add_favicon_to_header() {
	$favicon_url = apply_filters( 'rlje_main_favicon_url', get_bloginfo( 'template_url' ) . '/img/favicon.ico' );
	?>
	<link rel="shortcut icon" href="<?php echo esc_url( $favicon_url ); ?>">
	<?php
}
add_action( 'wp_head', 'add_favicon_to_header', 1 );


function add_theme_json_ld_to_header() {
	$json_ld = [];
	$json_ld['@context'] = 'http://schema.org';
	$json_ld['@type'] = 'Website';
	$json_ld['name'] = get_bloginfo( 'name' );
	$json_ld['url'] = get_bloginfo( 'url' );
	$json_ld['image'] = 'https://api.rlje.net/acorn/artwork/size/atvlogo?t=Icons&w=300';
	$json_ld['description'] = get_bloginfo( 'description' );
	$json_ld['publisher'] = [
		'@type' => 'Organization',
		'logo' => [
			'@type' => 'ImageObject',
			'url' =>'https://acorn.dev/wp-content/plugins/rlje-wp-plugin/rlje-theme-settings/themes/umc/img/logo.png',
			'name' => get_bloginfo( 'name' ),
			'width' => 300,
			'height' => 50,
		],
	];
	$json_ld = apply_filters( 'rlje_json_ld_header', $json_ld );
	?>
	<script type="application/ld+json">
		<?php echo wp_json_encode( $json_ld ); ?>
	</script>
	<?php
}
add_action( 'wp_head', 'add_theme_json_ld_to_header' );
