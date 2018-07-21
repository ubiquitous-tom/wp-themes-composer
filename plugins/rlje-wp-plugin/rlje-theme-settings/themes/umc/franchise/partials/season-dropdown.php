<?php
if ( ! empty( $franchise->seasons ) ) {
	$seasons = $franchise->seasons;
}

$seasons_count = count( $seasons );
if ( $seasons_count > 1 ) :
	?>
<!-- Drop Down Series Filter -->
<div id="cover">
	<div id="options">
		<a><?php echo esc_html( ( isset( $season_name ) ) ? $season_name : 'Filter By Series' ); ?></a>
		<span id="clicker">
			<img src="https://api.rlje.net/acorn/artwork/size/dropdown-arrow?t=Icons" width="13" style="opacity:.7"/>
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
