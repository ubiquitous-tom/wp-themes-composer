<?php

class RLJE_Header {

	protected $theme_settings;
	protected $sailthru;
	protected $google;
	protected $apple;
	protected $tealium;
	protected $franchise;


	public function __construct() {
		$this->sailthru = get_option( 'rlje_sailthru_settings' );
		$this->google   = get_option( 'rlje_google_settings' );
		$this->apple    = get_option( 'rlje_apple_settings' );
		$this->tealium  = get_option( 'rlje_tealium_settings' );

		add_action( 'wp_head', array( $this, 'initialize_headers' ) );

		add_action( 'wp_head', array( $this, 'add_sailthru_script' ) );
		add_action( 'wp_head', array( $this, 'add_tealium_script' ) );
		// add_action( 'wp_head', array( $this, 'check_first_time_visitor' ) );
		add_action( 'wp_head', array( $this, 'add_google_analytics_script' ) );
		add_action( 'wp_head', array( $this, 'add_google_play_app_meta_tag' ), 0 );
		add_action( 'wp_head', array( $this, 'add_google_site_verification_meta_tag' ), 0 );
		add_action( 'wp_head', array( $this, 'add_apple_itunes_app_meta_tag' ), 0 );
		// add_action( 'wp_head', array( $this, 'rlje_wp_title' ) );
		// add_filter( 'wp_title', array( $this, 'rlje_wp_title' ), 10, 3 );
		add_filter( 'document_title_separator', array( $this, 'rlje_title_separator' ) );
		add_filter( 'document_title_parts', array( $this, 'rlje_title_parts' ) );
		add_action( 'rlje_tag_mananger_iframe', array( $this, 'add_google_tag_manager_frame' ) );
		add_action( 'wp_head', array( $this, 'add_description_meta_tag' ), 1 );
	}

	public function initialize_headers() {
		$this->franchise = rljeApiWP_getFranchiseById( get_query_var( 'franchise_id' ) );
	}

	public function add_sailthru_script() {
		$sailthru_customer_id = ( ! empty( $this->sailthru['customer_id'] ) ) ? $this->sailthru['customer_id'] : '';
		if ( ! empty( $sailthru_customer_id ) ) : ?>
			<script src="https://ak.sail-horizon.com/spm/spm.v1.min.js"></script>
			<script>Sailthru.init({ customerId: '<?php echo $sailthru_customer_id; ?>' });</script>
			<?php
		endif;
	}

	public function add_tealium_script() {
		$tealium_id = ( ! empty( $this->tealium['tealium_id'] ) ) ? $this->tealium['tealium_id'] : '';
		if ( ! empty( $tealium_id ) ) :
			?>
		<script>
			var versaTag = {};
			versaTag.id = "<?php echo esc_attr( $tealium_id ); ?>";
			versaTag.sync = 0;
			versaTag.dispType = "js";
			versaTag.ptcl = "HTTPS";
			versaTag.bsUrl = "bs.serving-sys.com/BurstingPipe";
			//VersaTag activity parameters include all conversion parameters including custom parameters and Predefined parameters. Syntax: "ParamName1":"ParamValue1", "ParamName2":"ParamValue2". ParamValue can be empty.
			versaTag.activityParams = {
				//Predefined parameters:
				"Session":""
				//Custom parameters:
			};
			//Static retargeting tags parameters. Syntax: "TagID1":"ParamValue1", "TagID2":"ParamValue2". ParamValue can be empty.
			versaTag.retargetParams = {};
			//Dynamic retargeting tags parameters. Syntax: "TagID1":"ParamValue1", "TagID2":"ParamValue2". ParamValue can be empty.
			versaTag.dynamicRetargetParams = {};
			// Third party tags conditional parameters and mapping rule parameters. Syntax: "CondParam1":"ParamValue1", "CondParam2":"ParamValue2". ParamValue can be empty.
			versaTag.conditionalParams = {};
		</script>
		<script id="ebOneTagUrlId" src="https://secure-ds.serving-sys.com/SemiCachedScripts/ebOneTag.js"></script>
		<noscript>
			<iframe src="https://bs.serving-sys.com/BurstingPipe?cn=ot&amp;onetagid=6377&amp;ns=1&amp;activityValues=$$Session=[Session]$$&amp;retargetingValues=$$$$&amp;dynamicRetargetingValues=$$$$&amp;acp=$$$$&amp;" style="display:none;width:0px;height:0px"></iframe>
		</noscript>
			<?php
		endif;
	}

	// Check first time visitor to the site to redirect to signup have but WHY?!
	public function check_first_time_visitor() {
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		$parse_url   = parse_url( $current_url );
		if ( ( empty( $parse_url['hash'] ) ) && ( empty( $parse_url['path'] ) ) ) {
			if ( ! isset( $_COOKIE['visited'] ) ) {
				// Leave the else value empty to production, now is .dev because it is not implemented in prod yet (used in uat.acorn.tv).
				$environment  = apply_filters( 'atv_get_extenal_subdomain', '' );
				$redirect_url = esc_url( 'https://signup' . $environment . '.acorn.tv' );
				setcookie( 'visited', true );
				wp_safe_redirect( $redirect_url, 302 );
				exit();
			}
		}
		setcookie( 'visited', true );
	}

	public function add_google_analytics_script() {
		$google_analytics_id = ( ! empty( $this->google['google_analytics_id'] ) ) ? $this->google['google_analytics_id'] : '';
		if ( ! empty( $google_analytics_id ) ) :
			?>
		<!-- Google Tag Manager Script -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_attr( $google_analytics_id ); ?>');</script>
		<!-- End Google Tag Manager Script -->
			<?php
		endif;
	}

	public function add_google_play_app_meta_tag() {
		$google_play_app_id = ( ! empty( $this->google['google_play_app_id'] ) ) ? $this->google['google_play_app_id'] : '';
		if ( ! empty( $google_play_app_id ) ) :
			?>
			<meta name="google-play-app" content="<?php echo esc_attr( $google_play_app_id ); ?>">
			<?php
		endif;
	}

	public function add_google_site_verification_meta_tag() {
		$google_site_verification_id = ( ! empty( $this->google['google_site_verification_id'] ) ) ? $this->google['google_site_verification_id'] : '';
		if ( ! empty( $google_site_verification_id ) ) :
			?>
			<meta name="google-site-verification" content="<?php echo esc_attr( $google_site_verification_id ); ?>">
			<?php
		endif;
	}

	public function add_apple_itunes_app_meta_tag() {
		$apple_itunes_app_id = ( ! empty( $this->apple['apple_itunes_app_id'] ) ) ? $this->apple['apple_itunes_app_id'] : '';
		if ( ! empty( $apple_itunes_app_id ) ) :
			?>
			<meta name="apple-itunes-app" content="<?php echo esc_attr( $apple_itunes_app_id ); ?>">
			<?php
		endif;
	}

	public function rlje_title_separator( $sep ) {
		$sep = '|';

		return $sep;
	}

	public function rlje_title_parts( $title ) {
		// global $wp_query;
		// Add dynamic title and meta description when it is a franchise.
		/*
		$is_custom_meta_title_and_desc = false;
		if ( ! empty( $wp_query->query_vars['franchise_id'] ) ) {
			$franchise = rljeApiWP_getFranchiseById( $wp_query->query_vars['franchise_id'] );
			if ( is_object( $franchise ) ) {
				$is_custom_meta_title_and_desc = true;
				$meta_title                    = htmlentities( $franchise->name );
				$meta_descr                    = htmlentities( $franchise->longDescription );
			}
		} elseif ( get_query_var( 'post_type' ) === 'atv_landing_page' ) {
			$is_custom_meta_title_and_desc = true;
			$meta_title                    = htmlentities( get_the_title() );
			$meta_descr                    = htmlentities( get_the_excerpt() );
		}
		if ( $is_custom_meta_title_and_desc ) :
			remove_theme_support( 'title-tag' );
			?>
		<title>Acorn TV | <?php echo $meta_title; ?></title>
		<meta name="description" content="<?php echo $meta_descr; ?>">
			<?php
		endif;*/

		if ( ! empty( get_query_var( 'franchise_id' ) ) ) {
			$franchise = rljeApiWP_getFranchiseById( get_query_var( 'franchise_id' ) );
			if ( is_object( $franchise ) ) {
				$meta_title = htmlentities( $franchise->name );
				$meta_descr = htmlentities( $franchise->longDescription );
			}

			// UMC specific
			// $title['title'] = $meta_title;
			// $title['tagline'] = $title['site'] . ' - ' . get_bloginfo( 'description' );
			// unset( $title['site'] );
			$title['tagline'] = $meta_title;
		}

		// if ( 'atv_landing_page' === get_query_var( 'post_type' ) ) {
		// 	$meta_title = htmlentities( get_the_title() );
		// 	$meta_descr = htmlentities( get_the_excerpt() );

		// 	$title['tagline'] = $meta_title;
		// }

		// $title['tagline'] .= 'TOMTOM';

		return apply_filters( 'rlje_title', $title );
	}

	public function add_google_tag_manager_frame() {
		if ( ! empty( $this->google['google_analytics_id'] ) ) {
			?>
			<!-- Google Tag Manager iFrame -->
			<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $this->google['google_analytics_id'] ); ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager iFrame -->
			<?php
		}
	}

	public function add_description_meta_tag( $meta_descr = '' ) {
		if ( ! empty( get_query_var( 'franchise_id' ) ) ) {
			$franchise = rljeApiWP_getFranchiseById( get_query_var( 'franchise_id' ) );
			if ( is_object( $franchise ) ) {
				$meta_title = htmlentities( $franchise->name );
				$meta_descr = htmlentities( $franchise->longDescription );
			}

		}

		// if ( 'atv_landing_page' === get_query_var( 'post_type' ) ) {
		// 	$meta_title = htmlentities( get_the_title() );
		// 	$meta_descr = htmlentities( get_the_excerpt() );
		// }

		$meta_descr = apply_filters( 'rlje_description_meta_tag_content', $meta_descr );

		if ( ! empty( $meta_descr ) ) :
		?>
		<meta name="description" content="<?php echo esc_html( $meta_descr ); ?>">
		<?php
		endif;
	}
}

$rlje_header = new RLJE_Header();
