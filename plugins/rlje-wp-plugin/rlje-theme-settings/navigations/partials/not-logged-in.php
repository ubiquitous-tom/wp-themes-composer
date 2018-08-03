<ul class="navbar-right-ul">
	<li class="navbar-right hidden-md hidden-lg" >
		<a class="log-in" href="<?php echo esc_url( trailingslashit( home_url( 'signup' ) ) ); ?>"><?php echo esc_html( $navigation_text['signup'] ); ?></a>
	</li>
	<li class="navbar-right visible-md visible-lg">
		<a  class="free-month" href="<?php echo esc_url( trailingslashit( home_url( 'signup' ) ) ); ?>"><?php echo esc_html( $navigation_text['free_trial'] ); ?></a>
	</li>
	<li class="navbar-right">
		<a class="log-in" href="<?php echo esc_url( trailingslashit( home_url( 'signin' ) ) ); ?>"><?php echo esc_html( $navigation_text['login'] ); ?></a>
	</li>
</ul>
