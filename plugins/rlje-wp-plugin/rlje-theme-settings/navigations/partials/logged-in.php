<ul class="nav pull-right">
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-circle-o" aria-hidden="true"></i> <?php echo sprintf( __( 'My %s', 'acorntv' ), get_bloginfo( 'name' ) ); ?> <span class="caret"></span></a>
		<ul class="dropdown-menu dropdown-menu-right">
			<li><a href="<?php echo esc_url( $logged_in_link_text['recently_watched_link'] ); ?>"><?php esc_html_e( 'Recently Watched', 'acorntv' ); ?></a></li>
			<li><a href="<?php echo esc_url( $logged_in_link_text['my_watchlist_link'] ); ?>"><?php esc_html_e( 'My Watchlist', 'acorntv' ); ?></a></li>
			<li><a href="<?php echo esc_url( $logged_in_link_text['manage_account_link'] ); ?>"><?php esc_html_e( 'Manage Account', 'acorntv' ); ?></a></li>
			<li><a href="<?php echo esc_url( $logged_in_link_text['change_password_link'] ); ?>"><?php esc_html_e( 'Change Password', 'acorntv' ); ?></a></li>
			<?php if ( $web_payment_edit ) : ?>
			<li><a href="<?php echo esc_url( $logged_in_link_text['change_email_link'] ); ?>"><?php esc_html_e( 'Change Email', 'acorntv' ); ?></a></li>
			<?php endif; ?>
			<li><a href="<?php echo esc_url( $logged_in_link_text['log_out_link'] ); ?>"><?php esc_html_e( 'Log Out', 'acorntv' ); ?></a></li>
		</ul>
	</li>
</ul>
