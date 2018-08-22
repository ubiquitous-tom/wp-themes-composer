<?php
$base_url_path = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
?>
<div class="col-md-12 col-lg-6">
	<div class="home-hero-carousel">
		<div id="<?php echo sanitize_html_class( 'carousel_fade_' . $news_group );?>" class="carousel slide carousel-fade " data-pause="true"  data-interval="false" data-ride="carousel">
			<?php if ( count( $marketing_places ) > 1 ) : ?>
			<a class="left carousel-control" href="#<?php echo sanitize_html_class( 'carousel_fade_' . $news_group );?>" data-slide="prev" id="carousel-arrow">
				<img src="<?php echo esc_url( plugins_url( '../img/carousel-left.png', __FILE__ ) ); ?>" width="35px">
			</a>
			<a class="right carousel-control" href="#<?php echo sanitize_html_class( 'carousel_fade_' . $news_group );?>" data-slide="next" id="carousel-arrow">
				<img src="<?php echo esc_url( plugins_url( '../img/carousel-right.png', __FILE__ ) ); ?>" width="35px">
			</a>
			<?php endif; ?>

			<div class="carousel-inner">
			<?php foreach ( $marketing_places as $key => $item ) : ?>
				<div class="item <?php echo ( $key == 0 ) ? 'active' : ''; ?>">
					<?php if ( 'image' === $item['type'] ) : ?>
					<a <?php if ( ! empty( $item['franchiseId'] ) ) : ?> href="<?php echo esc_url( trailingslashit( home_url( $item['franchiseId'] ) ) ); ?>" <?php endif; ?>>
						<img alt="thumb marketing image" class="sliderimage" src="<?php echo esc_url( $item['src'] ); ?>">
					</a>
					<?php
						elseif ( 'extImage' === $item['type'] ) :
						$external_link = ( preg_match( '/^http[s]*\:[\/]{2}/i', $item['externalLink'] ) ) ? $item['externalLink'] : 'http://' . str_replace( [ ':', '//' ], '', $item['externalLink'] );
					?>
					<a <?php if ( ! empty( $item['externalLink'] ) ) : ?> href="<?php echo esc_url( $external_link ); ?>" <?php endif; ?> onclick="window.open('<?php echo esc_url( $external_link ); ?>', 'newwindow', 'scrollbars=yes,top=200, left=100,width=850, height=600'); return false;" target="_blank">
						<img alt="thumb marketing image" class="sliderimage" src="<?php echo $item['src']; ?>">
					</a>
					<?php else : ?>
					<div class="news-carousel">
						<!-- <iframe
							class="embed-responsive"
							style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;border:none;"
							src="//players.brightcove.net/3047407010001/r1ZjWi4Ab_default/index.html?videoId=<?php echo $item['src']; ?>"
							allowfullscreen=""
							webkitallowfullscreen=""
							mozallowfullscreen="">
						</iframe> -->
						<video preload
							id="brightcove-news-carousel-player"
							data-account="<?php echo esc_attr( $this->brightcove['bc_account_id'] ); ?>"
							data-player="<?php echo esc_attr( $this->brightcove['bc_player_id'] ); ?>"
							data-embed="default"
							data-video-id="ref:<?php echo esc_attr( $item['src'] ); ?>"
							class="video-js embed-responsive embed-responsive-16by9"
							controls></video>
					</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
