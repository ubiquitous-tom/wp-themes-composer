<ul class="navbar-right-ul accountUser">
	<li class="navbar-right">
		<div class="menuOptions">
			<span class="accountOptions hidden-md hidden-sm hidden-xs">My <?php bloginfo( 'name' ); ?></span>
			<span id="clicker"></span>
		</div>
		<ul class="drop-select closed">
			<li><a href="/browse/recentlywatched/">Recently Watched</a></li>
			<li><a href="/browse/yourwatchlist/">My Watchlist</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( home_url( 'account/status' ) ) ); ?>">Manage Account</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( home_url( 'account/editPassword' ) ) ); ?>">Change Password</a></li>
			<?php if ( $web_payment_edit ) : ?>
			<li><a href="<?php echo esc_url( trailingslashit( home_url( 'account/editEmail' ) ) ); ?>">Change Email</a></li>
			<?php endif; ?>
			<li><a href="<?php echo esc_url( trailingslashit( home_url( 'account/logout' ) ) ); ?>">Log Out</a></li>
		</ul>
	</li>
</ul>
