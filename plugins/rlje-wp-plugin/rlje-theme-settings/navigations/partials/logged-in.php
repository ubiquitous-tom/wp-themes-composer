<ul class="navbar-right-ul accountUser">
	<li class="navbar-right">
		<div class="menuOptions">
			<span class="accountOptions hidden-md hidden-sm hidden-xs">My Acorn Tv</span>
			<span id="clicker">
				<img width="18" class="accountIcon" src="https://api.rlje.net/acorn/artwork/size/account-icon?t=Icons">
			</span>
		</div>
		<ul class="drop-select closed">
			<li><a href="/browse/recentlywatched/">Recently Watched</a></li>
			<li><a href="/browse/yourwatchlist/">My Watchlist</a></li>
			<li><a href="<?php echo esc_url( 'https://account' . $environment . '.acorn.tv/#accountStatus' ); ?>">Manage Account</a></li>
			<li><a href="<?php echo esc_url( 'https://account' . $environment . '.acorn.tv/#editPassword' ); ?>">Change Password</a></li>
			<?php if ( $web_payment_edit ) : ?>
			<li><a href="<?php echo esc_url( 'https://account' . $environment . '.acorn.tv/#editEmail' ); ?>">Change Email</a></li>
			<?php endif; ?>
			<li><a href="<?php echo esc_url( 'https://account' . $environment . '.acorn.tv/#logout' ); ?>">Log Out</a></li>
		</ul>
	</li>
</ul>
