<?php
// $environment = apply_filters( 'atv_get_extenal_subdomain', '' ); // Leave the else value empty to production, now is .dev because it is not implemented in prod yet (used in uat.acorn.tv).
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="code" content="<?php echo ( $code = rljeApiWP_getCountryCode() ) ? $code : 'us'; ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-itunes-app" content="app-id=896014310">
	<meta name="google-play-app" content="app-id=com.acorn.tv">
	<meta name="google-site-verification" content="QCrNnLN11eCtEq_RIVjUQEXRabEJewu4tPwxbjJHHj4" />

	<?php
	// Add dynamic title and meta description when it is a franchise.
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
	endif;
	wp_head();
	// get_template_part( '/partials/redirect-signup' );
	// get_template_part( '/partials/google-analytics' );
	?>
</head>
<body <?php body_class(); ?>>
	<?php get_template_part( '/partials/smart-android-app-banner' ); ?>
	<?php
	/*
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
	</noscript> */
	?>
	<?php
	/*
	if ( defined( 'SAILTHRU_CUSTOMER_ID' ) && ! empty( SAILTHRU_CUSTOMER_ID ) ) : ?>
	<script src="https://ak.sail-horizon.com/spm/spm.v1.min.js"></script>
	<script>Sailthru.init({ customerId: '<?php echo SAILTHRU_CUSTOMER_ID; ?>' });</script>
	<?php endif; */
	?>
	<?php
	/*
	<!-- Fixed Bootstrap navbar -->
	<div class="navbar navbar-fixed-top" role="navigation">
		<div class="container">
		<?php
			$isUserLoggedAndActive = ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) );
		if ( $isUserLoggedAndActive ) :
			$webPaymentEdit = rljeApiWP_getWebPaymentEdit( $_COOKIE['ATVSessionCookie'] );
			?>
			<ul class="navbar-right-ul accountUser">
				<li class="navbar-right">
					<div class="menuOptions">
						<span class="accountOptions hidden-md hidden-sm hidden-xs">My Acorn Tv</span>
						<span id="clicker">
							<img width="18" class="accountIcon" src="https://api.rlje.net/acorn/artwork/size/account-icon?t=Icons">
						</span>
					</div>
					<ul class="drop-select closed">
						<a href="/browse/recentlywatched"><li>Recently Watched</li></a>
						<a href="/browse/yourwatchlist"><li>My Watchlist</li></a>
						<a href="https://account<?php echo $environment; ?>.acorn.tv/#accountStatus"><li>Manage Account</li></a>
						<a href="https://account<?php echo $environment; ?>.acorn.tv/#editPassword"><li>Change Password</li></a>
					<?php if ( $webPaymentEdit ) : ?>
						<a href="https://account<?php echo $environment; ?>.acorn.tv/#editEmail"><li>Change Email</li></a>
						<?php endif; ?>
						<a href="https://account<?php echo $environment; ?>.acorn.tv/#logout"><li>Log Out</li></a>
					</ul>
				</li>
			</ul>
		<?php else : ?>
			<ul class="navbar-right-ul">
				<li class="navbar-right hidden-md hidden-lg" ><a class="log-in" href="https://signup<?php echo $environment; ?>.acorn.tv/">SIGN UP</a></li>
				<li class="navbar-right visible-md visible-lg"> <a  class="free-month" href="https://signup<?php echo $environment; ?>.acorn.tv/">START FREE TRIAL</a></li>
				<li class="navbar-right"><a class="log-in" href="https://signup<?php echo $environment; ?>.acorn.tv/signin.html">LOG IN</a></li>
			</ul>
		<?php endif; ?>
			<div class="navbar-header">
				<a href="/"><img src="https://api.rlje.net/acorn/artwork/size/atvlogo?t=Icons&w=300" class="atv-logo"></a>
				<button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" type="button" class="navbar-toggle">
				<div class="button-bars">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				</button>
			</div>
			<div class="navbar-collapse side-collapse in">
				<ul class="nav navbar-nav">
					<form id="searchform" action="/" method="get" onsubmit="return search(this)">
						<button type="submit" id="search-button">
							<img src="https://api.rlje.net/acorn/artwork/size/search?t=Icons" width="23"/>
						</button>
						<input type="text" size="18" name="s" id="search-input" value="<?php echo ( ! empty( $wp_query->query_vars['search_text'] ) ) ? urldecode( $wp_query->query_vars['search_text'] ) : ''; ?>">
					</form>
					<li><a href="/">Home</a></li>
					<li><a href="/browse">Browse</a></li>
					<li><a href="/schedule">Schedule</a></li>
					<li><a href="http://support.acorn.tv">Help</a></li>
					<li><a id="store-link" href="https://store<?php echo $environment; ?>.acorn.tv"  style="color:#fff;" >Store</a> </li>
				</ul>
			</div><!--/.nav-collapse -->
			<div class="active-features">
				<?php
					$futureDate = rljeApiWP_getFutureDate();
				if ( $futureDate ) :
					?>
				<div class="navbar-future-date"><span>Future Date: <?php echo $futureDate; ?></span></div>
					<?php
					endif;
					$countryFilter = rljeApiWP_getCountryFilter();
				if ( $countryFilter ) :
					?>
				<div class="navbar-country-filter"><span>Country: <?php echo $countryFilter; ?></span></div>
					<?php
					endif;
					$isVideoDebuggerOn = rljeApiWP_isVideoDebuggerOn();
				if ( $isVideoDebuggerOn ) :
					?>
				<div class="navbar-video-debugging"><span>Video Debugger</span></div>
					<?php
					endif;
				?>
			</div>
		</div>
		<?php
		if ( $isUserLoggedAndActive && apply_filters( 'atv_browser_detection', $_SERVER['HTTP_USER_AGENT'] ) ) :
			?>
		<div id="upgradeMessage" class="red-box-alert">
			<a href="http://support.acorn.tv/support/solutions/articles/11000007247-what-browsers-does-acorn-tv-support-" class="red-box-message">
				For the best streaming experience, please upgrade your browser. Visit our Help Center to see how >
			</a>
			<div class="red-box-dismiss" role="button" onclick="dismissMessage()">
				<i class="fa fa-times-circle" aria-hidden="true"></i>
			</div>
		</div>
		<script type="text/javascript">
		function dismissMessage(){
			$('#upgradeMessage').remove();
			docCookies.setItem('dismissUpgradeMessage', 'on', 604800, '/', '.acorn.tv');
		}
		</script>
		<?php endif; ?>
	</div> */
	?>

	<?php do_action( 'rlje_header_navigation' ); ?>

