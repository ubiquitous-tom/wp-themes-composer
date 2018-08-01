<?php
get_header();
?>
<section id="signup">
	<div class="container">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<header class="row">
				<h3 class="text-center">Ready to start your free trial?</h3>
				<p class="text-center side">Watch the best in Black films &amp; television all in one place, always commercial free.</p>
			</header>
			<div id="progress-steps">show steps here</div>
			<form class="signup step-one row">
				<div class="form-group">
					<label for="signup-email">E-Mail Address *</label>
					<input id="signup-email" class="form-control" required name="signup_email" type="email">
				</div>
				<div class="form-group">
					<label for="signup-email-confirm">Confirm E-Mail Address *</label>
					<input id="signup-email-confirm" class="form-control" required name="signup_email_confirm" type="email">
				</div>
				<div class="form-group">
					<label for="signup-password">Password *</label>
					<input id="signup-password" class="form-control" required name="signup_password" type="password" minlength="6" placeholder="6 characters minimum">
				</div>
				<div class="form-group">
					<label for="signup-password-confirm">Confirm Password *</label>
					<input id="signup-password-confirm" class="form-control" required name="signup_password_confirm" type="password" minlength="6" placeholder="6 characters minimum">
				</div>
				<div class="checkbox">
					<label>
						<input checked type="checkbox">Sign me up for the <?php bloginfo( 'name' ); ?> newsletter
					</label>
				</div>
				<button class="btn btn-primary btn-lg center-block">Continue to step 2 →</button>
			</form>
		</div>
	</div>
</section>

<?php
get_footer();
