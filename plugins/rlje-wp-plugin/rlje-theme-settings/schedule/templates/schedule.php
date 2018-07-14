<?php
get_header();
$base_url_path   = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
// $active_section = get_query_var( 'section' );
$list_sections  = array(
	'featured'    => 'Recently Added',
	'comingsoon'  => 'Coming Soon',
	'leavingsoon' => 'Leaving Soon',
);
?>
<div class="container schedule">
	<ul class="subnav">
		<span id="page-subhead" style="padding-bottom:24px;">FILTER BY:</span>
		<?php foreach ( $list_sections as $section_key => $section_name ) : ?>
		<li style="padding-right:25px;"
			<?php
			if ( $section_key === $active_section ) :
				?>
			class="active"<?php endif; ?>><a href="
							<?php
								echo $base_url_path . '/schedule/';
								echo ( $section_key != 'featured' ) ? $section_key : '';
							?>
"><?php echo $section_name; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
	$have_franchises_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'section' );
	if ( $have_franchises_available ) :
		$a_schedule_items = rljeApiWP_getScheduleItems( $active_section );
		if ( isset( $a_schedule_items ) && count( $a_schedule_items ) > 0 ) :
			$total_franchises_result = count( $a_schedule_items );
			foreach ( $a_schedule_items as $key => $item ) :
				$img = 'https://api.rlje.net/acorn/artwork/size/' . $item->image . '?w=750';
				if ( $key % 2 == 0 ) :
					?>
	<div class="row">
	<?php endif; ?>
		<div class="col-md-6" itemprop="containsSeason" itemscope itemtype="http://schema.org/TVSeries">
				<?php if ( isset( $item->trailerId ) && ( ! empty( $item->trailerId ) ) ) : ?>
			<span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
				<meta itemprop="thumbnailUrl" content="<?php echo $img; ?>" />
				<meta itemprop="description" content="<?php echo $item->longDescription; ?>" />
				<meta itemprop="name" content="<?php echo $item->name; ?>" />
				<meta itemprop="uploadDate" content="<?php echo ( isset( $item->startDate ) && $item->startDate != '' ) ? date( 'Y-m-d', $item->startDate ) : ''; ?>" />
			</span>
			<div class="video" data-embedcode="<iframe style='border:none;z-index:4' src='//players.brightcove.net/3392051363001/2f9624d6-0dd2-46ff-9843-dadffb653bc3_default/index.html?videoId=<?php echo $item->trailerId; ?>'
				allowfullscreen
				webkitallowfullscreen
				mozallowfullscreen></iframe>">
				<img title="<?php echo $item->name; ?>" alt="thumb image" class="wp-post-image" src="<?php echo $img; ?>" style="width:100%; height:auto;z-index:1;opacity:.75">
				<button class="transparent js-play"><img height="35" src="https://api.rlje.net/acorn/artwork/size/play-icon?t=Icons" style="opacity:1"><span>Watch Trailer</span></button>
			</div>
			<!-- <div id="trailer-video" class="video">
				<video
					id="brightcove-trailer-player"
					data-account="3392051363001"
					data-player="0066661d-8f08-4e7b-a5b4-8d48755a3057"
					data-embed="default"
					data-video-id="ref:<?php echo $trailerId; ?>"
					poster="<?php echo apply_filters( 'atv_get_image_url', $franchise->image . '?w=750' ); ?>"
					class="video-js embed-responsive embed-responsive-16by9"
					controls></video>
			</div> -->
			<?php else : ?>
			<div class="video">
				<img title="<?php echo $item->name; ?>" alt="thumb image" itemprop="image" class="wp-post-image" src="<?php echo $img; ?>" style="width:100%; height:auto;z-index:1;opacity:.75">
			</div>
			<?php endif; ?>
			<div class="franchise-eps-bg"  style="margin-bottom:25px">
				<h5 itemprop="name"><?php echo $item->name; ?></h5>
				<p itemprop="description"><?php echo $item->longDescription; ?></p>
			</div>
		</div>
				<?php if ( ( $key + 1 ) % 2 == 0 || $key == $total_franchises_result - 1 ) : ?>
	</div>
	<?php endif; ?>
				<?php
			endforeach;
		else :
			set_query_var( 'no_result_inline', true );
			get_template_part( 'partials/no-result-message' );
		endif;
	else :
		get_template_part( 'partials/franchises-unavailable-message' );
	endif;
	?>
</div>
<?php
get_footer();
