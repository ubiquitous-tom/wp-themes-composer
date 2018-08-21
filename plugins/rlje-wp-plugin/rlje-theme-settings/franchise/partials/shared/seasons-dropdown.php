<?php
// global $wp_query;

// $base_url_path = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
// $season_name   = isset( $wp_query->query_vars['seasonName'] ) ? $wp_query->query_vars['seasonName'] : null;
// $seasons       = $wp_query->query_vars['seasons'];
// $franchise_id  = $wp_query->query_vars['franchise_id'];

$filter_by_text = apply_filters( 'rlje_seasons_dropdown_filter_by_text', 'Filter By Series' );

// $season_name = get_q
if ( ! empty( $franchise->seasons ) ) {
	$seasons = $franchise->seasons;
}

$seasons_count = count( $seasons );
if ( $seasons_count > 1 ) :
	?>
<!-- Drop Down Series Filter -->
<div id="cover">
	<div id="options">
		<a><?php echo esc_html( ( isset( $season_name ) ) ? $season_name : $filter_by_text ); ?></a>
		<span id="clicker">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/img/dropdown-arrow.png' ); ?>" width="13" style="opacity:.7"/>
		</span>
	</div>
	<ul id="drop-select" class="closed">
		<?php for ( $i = 0; $i < $seasons_count; $i++ ) : ?>
		<li>
			<a href="<?php echo esc_url( home_url( trailingslashit( $franchise_id . '/' . rljeApiWP_convertSeasonNameToURL( $seasons[ $i ]->name ) ) ) ); ?>">
				<?php echo $seasons[ $i ]->name; ?>
			</a>
		</li>
		<?php endfor; ?>
		<li>
			<a href="<?php echo esc_url( home_url( trailingslashit( $franchise_id ) ) ); ?>">View All</a>
		</li>
	</ul>
</div>

	<?php
endif;
