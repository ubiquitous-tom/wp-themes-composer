<?php

class RLJE_Header {

	private $theme_settings;
	private $sailthru;

	public function __construct() {
		$this->sailthru = get_option( 'rlje_sailthru_settings' );
		$this->google = get_option( 'rlje_google_settings' );

		add_action( 'wp_head', array( $this, 'add_sailthru_script' ) );
		add_action( 'wp_head', array( $this, 'add_tealium_script' ) );
		add_action( 'wp_head', array( $this, 'check_first_time_visitor' ) );
		add_action( 'wp_head', array( $this, 'add_google_analytics_script' ) );
	}

	public function add_sailthru_script() {
		$sailthru_customer_id = ( ! empty( $this->sailthru['customer_id'] ) ) ? $this->sailthru['customer_id'] : '';
		if ( ! empty( $sailthru_customer_id ) ) : ?>
			<script src="https://ak.sail-horizon.com/spm/spm.v1.min.js"></script>
			<script>Sailthru.init({ customerId: '<?php echo $sailthru_customer_id; ?>' });</script>
		<?php endif;
	}

	public function add_tealium_script() {
		?>
		<script>
			var versaTag = {};
			versaTag.id = "6377";
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
	}

	// Check first time visitor to the site to redirect to signup have but WHY?!
	public function check_first_time_visitor() {
		global $wp;
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
		$parse_url = parse_url( $current_url );
		if ( ( empty( $parse_url['hash'] ) ) && ( empty( $parse_url['path'] ) ) ) {
			if ( ! isset( $_COOKIE['visited'] ) ) {
				// Leave the else value empty to production, now is .dev because it is not implemented in prod yet (used in uat.acorn.tv).
				$environment = apply_filters( 'atv_get_extenal_subdomain', '' );
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
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $google_analytics_id ); ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_attr( $google_analytics_id ); ?>');</script>
		<!-- End Google Tag Manager -->
			<?php
		endif;
	}
}

$rlje_header = new RLJE_Header();
