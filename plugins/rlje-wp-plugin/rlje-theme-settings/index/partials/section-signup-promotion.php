<?php
$brightcove_settings   = get_option( 'rlje_theme_brightcove_shared_settings' );
$brightcove_account_id = $brightcove_settings['shared_account_id'];
$brightcove_player_id  = $brightcove_settings['shared_player_id'];
$about_vide_id         = '5180867444001';
?>
<section id="home-signup-promotion">
	<div class="container">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="row">
				<div class="col-md-5">
					<p class="embed-responsive embed-responsive-16by9">
						<video
							id="umc-about"
							data-video-id="<?php echo $about_vide_id; ?>"
							data-account="<?php echo $brightcove_account_id; ?>"
							data-player="<?php echo $brightcove_player_id; ?>"
							data-embed="default"
							class="vide-js embed-responsive-item"
							controls
						></video>
					</p>
				</div>
				<div class="col-md-7">
					<h5>Start your FREE 7-day trial to watch the best in Black film & television with new and exclusive content added weekly! Download UMC on your favorite Apple and Android mobile devices or stream on Roku or Amazon Prime Video Channels. Drama, romance, comedy and much more - it’s all on UMC!</h5>
					<a class="btn btn-primary" href="/sign-up/">
						<i class="fa fa-play" aria-hidden="true"></i>Sign Up now!
					</a>
				</div>
				<a href="btn btn-primary btn-lg"></a>
			</div>
		</div>
	</div>
</section>
