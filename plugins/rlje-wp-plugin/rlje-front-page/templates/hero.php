<section class="home-hero-carousel">
	<div class="carousel feature-slide slide carousel-fade" id="heroCarousel">
		<div class="carousel-inner">
			<?php
			$i = 0;
			$total_items = count( $data_carousel );
			foreach ( $data_carousel as $item ) :
				$prev_item = ( $i > 0 ) ? $data_carousel[ $i - 1 ] : $data_carousel[ $total_items - 1 ];
				$next_item = ( $i < $total_items - 1 ) ? $data_carousel[ $i + 1 ] : $data_carousel[0];

				// Gets Links.
				$prev_link = apply_filters( 'atv_heroCarusel_link', $prev_item );
				$next_link = apply_filters( 'atv_heroCarusel_link', $next_item );
				$curr_link = apply_filters( 'atv_heroCarusel_link', $item );

				// Images.
				$curr_img = ( ! empty( $item->image ) ) ? $item->image : '';
				$prev_img = ( ! empty( $prev_item->image ) ) ? $prev_item->image : '';
				$next_img = ( ! empty( $next_item->image ) ) ? $next_item->image : '';

				$i++;
			?>
			<div class="item <?php echo ( $item === reset( $data_carousel ) ) ? 'active' : ''; ?>">
				<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 left">
					<a href="<?php echo esc_url( $prev_link ); ?>">
						<img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $prev_img.'?t=Mobile' ); ?>" style="width:100%; height:auto; ">
						<img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $prev_img.'?t=Web3' ); ?>" style="width:100%; height:auto; ">
					</a>
				</div>
				<div class="container">
					<a href="<?php echo esc_url( $curr_link ); ?>">
						<img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $curr_img.'?t=Mobile' ); ?>" style="width:100%; height:auto; ">
						<img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $curr_img.'?t=Web3' ); ?>" style="width:100%; height:auto; ">
					</a>
				</div>
				<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 right">
					<a href="<?php echo esc_url( $next_link ); ?>">
						<img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $next_img.'?t=Mobile' ); ?>" style="width:100%; height:auto; ">
						<img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices( $next_img.'?t=Web3' ); ?>" style="width:100%; height:auto; ">
					</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="container">
			<div class="control-position">
				<a class="left carousel-control" href="#heroCarousel" data-slide="prev">
					<img class="hero-left-arrow" src="<?php echo rljeApiWP_getImageUrlFromServices( 'hero-left?t=Icons' ); ?>"/>
				</a>
			</div>

			<div class="control-position">
				<a class="right carousel-control" href="#heroCarousel" data-slide="next">
					<div>
						<img class="hero-right-arrow" src="<?php echo rljeApiWP_getImageUrlFromServices( 'hero-right?t=Icons' ); ?>"/>
					</div>
				</a>
			</div>

			<ol class="carousel-indicators">
				<?php
				$data_carousel_count = count( $data_carousel );
				for ( $i = 0; $i < $data_carousel_count; $i++ ) :
				?>
				<li data-target="#heroCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo ( $i === 0 ) ? 'active' : ''; ?>"></li>
				<?php endfor; ?>
			</ol>
		</div>
	</div>
</section>
