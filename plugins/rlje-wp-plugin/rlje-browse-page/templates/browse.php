<?php

$rlje_api_wp_is_user_active = false;
$atv_session_cookie         = null;
if ( ! empty( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
	$atv_session_cookie         = $_COOKIE['ATVSessionCookie'];
	$rlje_api_wp_is_user_active = true;
}

$list_sections = array();
$guide_items   = array();

$guide_obj = rljeApiWP_getBrowseItems( 'guide' );
if ( ! empty( $guide_obj->options ) && is_array( $guide_obj->options ) ) {
	$guide_items = $guide_obj->options;
	foreach ( $guide_items as $guide ) {
		$browse_id                   = apply_filters( 'atv_get_browse_section_id', $guide->id );
		$list_sections[ $browse_id ] = $guide->name;
	}
}


// Set active section
$active_section = get_query_var( 'section' );

$is_order_by_enabled = true;

if ( empty( $active_section ) || $active_section === 'recentlywatched' || $active_section === 'yourwatchlist' ) {
	$is_order_by_enabled = false;
}

if ( $rlje_api_wp_is_user_active ) {
	$list_sections = array_merge(
		array(
			'recentlywatched' => 'Recently Watched',
			'yourwatchlist'   => 'My Watchlist',
		),
		$list_sections
	);
} elseif ( $active_section === 'recentlywatched' || $active_section === 'yourwatchlist' ) {
	wp_safe_redirect( home_url( 'browse' ) );
	exit;
}
$list_sections = array_merge( array( 'all' => 'All Shows' ), $list_sections ); // Add All Shows always as first item.

if ( isset( $list_sections[ $active_section ] ) || empty( $active_section ) ) :
	get_header();
	?>
<!-- Filter JS Sub Navigation base on category -->
<section class="browse">
	<div class="container">
		<ul class="subnav">
			<?php foreach ( $list_sections as $key => $property ) : ?>
			<li class="browse-menu<?php echo ( $key === $active_section ) ? ' active' : ''; ?>"><a href="<?php echo esc_url( '/browse/' . $key . '/' ); ?>"><?php echo $property; ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php
			$have_franchises_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'section' );
		if ( $have_franchises_available ) :
			if ( $is_order_by_enabled ) :
				?>
		<div id="page-subhead" class="browse-order">
			<span>SORT BY:</span>
			<a class="browse-order-option active js-orderby-added" href="#date-added">Date Added</a>
			<a class="browse-order-option js-orderby-az" href="#a-z">A to Z</a>
		</div>
				<?php
				endif;
			if ( empty( $active_section ) ) :
				// Show initial Browse Page.
				set_query_var( 'spotlight_items', $guide_items );
				// get_template_part( 'partials/browse-initial-carousel' );
				require plugin_dir_path( __FILE__ ) . '../partials/browse-initial-carousel.php';
				else :
					// Show Browse Page filtered
					?>
		<!-- Category Content Column Grid -->
		<div class="objects" >
					<?php
					$array_list_items = array();
					switch ( $active_section ) {
						case 'all':
							unset( $list_sections['all'] );
							if ( $rlje_api_wp_is_user_active ) {
								unset( $list_sections['recentlywatched'] );
								unset( $list_sections['yourwatchlist'] );
							}
							foreach ( $list_sections as $key => $value ) {
								$list_section_keys[] = apply_filters( 'atv_convert_browseSlug_to_contentID', $key );
							}
							$list_items       = rljeApiWP_getBrowseAllBySection( $list_section_keys, $atv_session_cookie );
							$array_list_items = rljeApiWP_orderFranchisesByCreatedDate( $list_items );
							break;
						case 'recentlywatched':
							$array_list_items = rljeApiWP_getUserRecentlyWatched( $atv_session_cookie );
							break;
						case 'yourwatchlist':
							$array_list_items = rljeApiWP_getUserWatchlist( $atv_session_cookie );
							break;
						default:
							$list_items       = rljeApiWP_getItemsByCategoryOrCollection( apply_filters( 'atv_convert_browseSlug_to_contentID', $active_section ) );
							$array_list_items = rljeApiWP_orderFranchisesByCreatedDate( $list_items );
							break;
					}
					set_query_var( 'array_list_items', $array_list_items );
					?>
					<div class="item" style="margin-left:0px;width:100%;padding-bottom:30px;">
						<?php //get_template_part( 'partials/list-browse-items' ); ?>
						<?php require plugin_dir_path( __FILE__ ) . '../partials/list-browse-items.php'; ?>
					</div>
		</div>
					<?php
				endif;
			else :
				// get_template_part( 'partials/franchises-unavailable-message' );
				// require plugin_dir_path( __FILE__ ) . '../partials/franchises-unavailable-message.php';
			?>
				<div id="contentPane" class="message-block">
					<div class="row-fluid">
						<p>We’re sorry, but there are no shows to display at this time.</p>
					</div>
				</div>
			<?php
			endif;
			?>
	</div>
</section>
	<?php
	get_footer();
// else :
// 	require_once get_404_template();
endif;
