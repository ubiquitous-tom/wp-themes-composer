<?php
$base_url_path = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
if ( count( $_POST ) > 0 && isset( $_COOKIE['ATVSessionCookie'], $_POST['action'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) :

	switch ( $_POST['action'] ) {
		case 'add':
			rljeApiWP_addToWatchlist( $_POST['franchise'], $_COOKIE['ATVSessionCookie'] );
			break;
		case 'remove':
			rljeApiWP_removeFromWatchlist( $_POST['franchise'], $_COOKIE['ATVSessionCookie'] );
			break;
	}

else :

	if ( function_exists( 'rljeApiWP_getFranchiseById' ) ) :
		$franchise_id = get_query_var( 'franchise_id' );
		$franchise   = rljeApiWP_getFranchiseById( $franchise_id );

		if ( isset( $franchise->id ) ) :
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				$get_stream_positions = rljeApiWP_getStreamPositionsByFranchise( $franchise_id, $_COOKIE['ATVSessionCookie'] );
				if ( isset( $get_stream_positions->streamPositions ) ) {
					$stream_positions = [];
					$count_positions  = 1;
					foreach ( $get_stream_positions->streamPositions as $stream_position ) {
						$stream_positions[ $stream_position->EpisodeID ] = [
							'Position'      => $stream_position->Position,
							'EpisodeLength' => $stream_position->EpisodeLength,
							'Counter'       => $count_positions,
						];
						$count_positions++;
					}
				}
			}
			$total_episodes = 0;
			foreach ( $franchise->seasons as $season_item ) {
				$total_episodes += count( $season_item->episodes );
			}
			get_header();
			$have_franchises_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'franchise' );
			$franchise_url            = $base_url_path . '/' . $franchise->id;

			if ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && ( ! empty( $franchise->episodes[0]->id ) ) ) {
				$franchise_art_link  = $franchise_url . '/trailer';
				$franchise_art_title = 'Click to view trailer';
			}
			if ( isset( $stream_positions ) ) {
				$stream_position_keys = array_keys( $stream_positions );
				$stream_position_data = array_values( $stream_positions );
				$break              = false;
				if ( $stream_position_data[0]['Position'] < ( $stream_position_data[0]['EpisodeLength'] - 60 ) ) {
					// Select the episode to continue watching when artwork is clicked.
					foreach ( $franchise->seasons as $season ) {
						foreach ( $season->episodes as $key_episode => $episode ) {
							if ( $episode->id === $stream_position_keys[0] ) {
								$episode_number    = apply_filters( 'atv_get_episode_number', $episode, ( $key_episode + 1 ) );
								$franchise_art_link = $franchise_url . '/' . rljeApiWP_convertSeasonNameToURL( $season->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $episode->name );

								$franchise_art_title = 'Click to continue watching ' . $season->name . ': Episode ' . $episode_number;
								$break             = true;
								break;
							}
						}
						if ( $break ) {
							break;
						}
					}
				} else {
					// Select the next episode to watch when artwork is clicked.
					$is_next_episode = false;
					foreach ( $franchise->seasons as $season ) {
						foreach ( $season->episodes as $key_episode => $episode ) {
							$episode_number          = apply_filters( 'atv_get_episode_number', $episode, ( $key_episode + 1 ) );
							$is_set_position_and_length = isset( $stream_positions[ $episode->id ], $stream_positions[ $episode->id ]['Position'], $stream_positions[ $episode->id ]['EpisodeLength'] );
							$isResume               = ( ! isset( $stream_positions[ $episode->id ] ) || ( $is_set_position_and_length && $stream_positions[ $episode->id ]['Position'] < ( $stream_positions[ $episode->id ]['EpisodeLength'] - 60 ) ) );
							if ( $is_next_episode && $isResume ) {
								$franchise_art_link  = $franchise_url . '/' . rljeApiWP_convertSeasonNameToURL( $season->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $episode->name );
								$franchise_art_title = 'Click to watch ' . $season->name . ': Episode ' . $episode_number;
								$break             = true;
								break;
							}
							if ( ! $is_next_episode ) {
								$is_next_episode = ( $episode->id === $stream_position_keys[0] );
							}
						}
						if ( $break ) {
							break;
						}
					}
				}
			} elseif ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && ( ! empty( $franchise->episodes[0]->id ) ) ) {
				$franchise_art_link  = $franchise_url . '/' . rljeApiWP_convertSeasonNameToURL( $franchise->seasons[0]->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $franchise->seasons[0]->episodes[0]->name );
				$franchise_art_title = 'Click to watch the first episode';
			}
			?>
<div itemscope itemtype="http://schema.org/TVSeries">
	<div class="secondary-bg" style="padding-bottom:50px">
		<div class="container franchise">
			<h4 class="subnav">
				<span class="subnav-prev hidden-xs hidden-sm">
					<a href="<?php echo $base_url_path; ?>">
						<img src="https://api.rlje.net/acorn/artwork/size/left-arrow?t=Icons" id="archive-arrows">
						<span>Back to Home</span>
					</a>
				</span>
				<span itemprop="name"><?php echo $franchise->name; ?></span>
				<meta itemprop="numberOfEpisodes" content="<?php echo $total_episodes; ?>" />
				<meta itemprop="numberOfSeasons" content="<?php echo count( $franchise->seasons ); ?>" />
				<span  class="subnav-next hidden-xs hidden-sm">
					<?php if ( ! empty( $first_episode_link ) ) : ?>
					<a href="<?php echo esc_url( trailingslashit( $first_episode_link ) ); ?>">
						<span>Watch Episode</span>
						<img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" id="archive-arrows">
					</a>
					<?php endif; ?>
				</span>
			</h4>
			<div class="row" >
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<?php if ( ! empty( $franchise_art_link ) ) : ?>
					<a href="<?php echo $franchise_art_link; ?>" class="js-play-resume">
						<img itemprop="image" class="wp-post-image" id="franchise-avatar" title="<?php echo $franchise_art_title; ?>" src="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=460" />
					</a>
					<?php else : ?>
					<img itemprop="image" class="wp-post-image" id="franchise-avatar" src="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=460" />
					<?php endif; ?>
				</div>
				<div class="col-xs-12 col-sm-6 col-lg-8" >
					<p id="franchise-description" itemprop="description"><?php echo $franchise->longDescription; ?></p>
					<?php
					if ( isset( $_COOKIE['ATVSessionCookie'], $franchise->id ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) && $have_franchises_available ) :
						if ( ! rljeApiWP_isFranchiseAddedToWatchlist( $franchise->id, $_COOKIE['ATVSessionCookie'] ) ) :
							?>
					<a class="inline"><button id="watchlistActionButton" onclick="addToWatchlist('<?php echo $franchise->id; ?>')">Add to Watchlist</button></a>
					<?php else : ?>
					<a class="inline"><button id="watchlistActionButton" onclick="removeFromWatchlist('<?php echo $franchise->id; ?>')">Remove from Watchlist</button></a>
					<?php endif; ?>
						<?php
						endif;
					?>
					<?php if ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && ( ! empty( $franchise->episodes[0]->id ) ) ) : ?>
					<span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
						<meta itemprop="thumbnailUrl" content="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=750" />
						<meta itemprop="description" content="<?php echo $franchise->longDescription; ?>" />
						<meta itemprop="name" content="<?php echo $franchise->name; ?>" />
						<meta itemprop="uploadDate" content="<?php echo ( isset( $franchise->episodes[0]->startDate ) && $franchise->episodes[0]->startDate != '' ) ? date( 'Y-m-d', $franchise->episodes[0]->startDate ) : ''; ?>" />
						<a class="inline" href="<?php echo $franchise_url . '/trailer/'; ?>">
							<button>View Trailer</button>
						</a>
					</span>
						<?php
						endif;
						set_query_var( 'seasons', $franchise->seasons );
						get_template_part( 'partials/seasons-dropdown' );
?>
				</div>
			</div>
		</div>
	</div>

			<?php
			if ( $have_franchises_available ) :
				if ( isset( $stream_positions ) ) :
					?>
	<!-- Continue Watching  -->
	<div class="container">
					<?php
					set_query_var( 'continueWatchingItems', $franchise->seasons );
					set_query_var( 'totalEpisodes', $total_episodes );
					set_query_var( 'streamposition', $stream_positions );
					get_template_part( 'partials/continue-watching-carousel' );
					?>
	</div>
					<?php
				endif;
				?>
	<div class="container episode">

		<!-- Seasons and Episodes -->
				<?php
				foreach ( $franchise->seasons as $season_key => $season ) {
					$season->seasonNumber = $season_key + 1;
					set_query_var( 'season', $season );
					if ( isset( $stream_positions ) ) {
						set_query_var( 'streamPositions', $stream_positions );
					}
					get_template_part( 'partials/list-episode-items' );
				}
				?>

		<!-- Viewers Also Watched -->
				<?php
				set_query_var( 'also_watched_items', rljeApiWP_getViewersAlsoWatched( $franchise_id ) );
				get_template_part( 'partials/viewers-also-watched' );
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
		else :
			require_once get_404_template();
		endif;
	else :
		get_header();
		get_template_part( 'partials/plugin-deactivated-message' );
		get_footer();
	endif;
endif;
