<?php
get_header();
?>
<section id="signup">
	<div class="container">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<header class="row">
				<h3 class="text-center">Ready to start your free trial?</h3>
				<p class="text-center">Watch the best in Black films &amp; television all in one place, always commercial free.</p>
			</header>
			<div id="progress-steps">show steps here</div>
			<div id="signup-step-one" class="row">
				<form action="">
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
						<input id="signup-password" class="form-control" required name="signup_password" type="password">
					</div>
					<div class="form-group">
						<label for="signup-password-confirm">Confirm Password *</label>
						<input id="signup-password-confirm" class="form-control" required name="signup_password_confirm" type="password">
					</div>
					<div class="form-group">
						<label for="signup-promo-code">Code</label>
						<input id="signup-promo-code" class="form-control" name="signup_promo_code" type="text">
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
	</div>
</section>

<?php
get_footer();
