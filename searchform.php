<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" class="search-field form-control" placeholder="Search &hellip;" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" >
	</label>
	<input type="submit" class="search-submit btn btn-default" value="Search">
</form>
