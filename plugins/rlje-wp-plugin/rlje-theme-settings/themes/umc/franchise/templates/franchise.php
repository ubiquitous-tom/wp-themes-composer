<?php
get_header();
$have_franchises_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'franchise' );
$franchise_url             = home_url( $franchise->id );
$franchise_image     = apply_filters( 'rlje_franchise_artwork', $franchise->image, $franchise );
$franchise_wallpaper       = rljeApiWP_getImageUrlFromServices( $franchise_image . '?t=franchise-wallpaper' );
?>
<div id="franchise-container" itemscope itemtype="http://schema.org/TVSeries">
	<div class="secondary-bg" style="background-image: linear-gradient(to top, rgba(1, 31, 54, 1) 20%, rgba(256, 0, 0, 0) 90%), url('<?php echo esc_url( $franchise_wallpaper ); ?>')">
		<div class="container franchise">
			<!-- <p id="franchise-cast" itemprop="cast"><?php echo esc_html( $franchise->longDescription ); ?></p> -->
			<h4 class="subnav hidden">
				<span class="subnav-prev hidden-xs hidden-sm">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="https://api.rlje.net/acorn/artwork/size/left-arrow?t=Icons" id="archive-arrows">
						<span>Back to Home</span>
					</a>
				</span>
				<span itemprop="name"><?php echo $franchise->name; ?></span>
				<meta itemprop="numberOfEpisodes" content="<?php echo $total_episodes; ?>" />
				<meta itemprop="numberOfSeasons" content="<?php echo count( $franchise->seasons ); ?>" />
				<span class="subnav-next hidden-xs hidden-sm">
					<?php if ( ! empty( $first_episode_link ) ) : ?>
					<a href="<?php echo esc_url( trailingslashit( $first_episode_link ) ); ?>">
						<span>Watch Episode</span>
						<img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" id="archive-arrows">
					</a>
					<?php endif; ?>
				</span>
			</h4>
			<div class="row" >
				<?php require_once plugin_dir_path( __FILE__ ) . '../partials/synopsis.php'; ?>
			</div>
		</div>
	</div>

	<?php
	if ( $have_franchises_available ) :
		if ( ! empty( $stream_positions ) ) :
			?>
	<!-- Continue Watching  -->
	<div class="container">
		<?php require_once plugin_dir_path( __FILE__ ) . '../partials/continue-watching-carousel.php'; ?>
	</div>
			<?php
		endif;
		?>
	<div class="container episode">

		<!-- Seasons and Episodes -->
		<?php
		foreach ( $franchise->seasons as $season_key => $season ) {
			$season->seasonNumber = $season_key + 1;
			// set_query_var( 'season', $season );
			// if ( isset( $stream_positions ) ) {
			// set_query_var( 'streamPositions', $stream_positions );
			// }
			// get_template_part( 'partials/list-episode-items' );
			require plugin_dir_path( __FILE__ ) . '../partials/list-episode-items.php';
		}
		?>

		<!-- Viewers Also Watched -->
		<?php
		// THIS DID NOTHING. WHY IS IT HERE.
		// set_query_var( 'also_watched_items', rljeApiWP_getViewersAlsoWatched( $franchise_id ) );
		// get_template_part( 'partials/viewers-also-watched' );
		?>
	</div>
		<?php
	else :
		get_template_part( 'partials/franchises-unavailable-message' );
	endif;
	?>
</div>
<?php
get_footer();


