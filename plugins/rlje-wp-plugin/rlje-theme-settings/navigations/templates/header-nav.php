
<!-- Fixed Bootstrap navbar -->
<div class="navbar navbar-fixed-top" role="navigation">
	<div class="container">

		<?php do_action( 'rlje_before_header_navigation' ); ?>

		<div class="navbar-header">
			<a href="/"><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../img/logo.png'); ?>" class="atv-logo"></a>
			<button data-toggle="collapse-side" data-target=".side-collapse" data-target-2=".side-collapse-container" type="button" class="navbar-toggle">
			<div class="button-bars">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</div>
			</button>
		</div>

		<?php wp_nav_menu( $menu_args ); ?>

		<?php do_action( 'rlje_after_header_navigation' ); ?>

	</div>
</div>
