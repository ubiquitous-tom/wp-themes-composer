<?php
// $environment   = apply_filters( 'atv_get_extenal_subdomain', '' );
// $base_url_path = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
?>

<?php /*
<div class="sub-footer">
	<div class="container" style="margin-bottom: 45px; margin-top: 45px;">
		<div id="signupNewsletter" class="visible-lg col-lg-4" style="float:right;">
			<h5>Sign up for our newsletter</h5>
			<input id="signupEmail" onfocus="clearPlaceholder(this)" onblur="emailPlaceholder(this)" type="text" value="Enter Your Email Address" style="border: medium none;border-radius: 0;color: #666;height: 40px;padding: 5px;width: 80%;font-size:13.5px;min-width:225px">
			<button onclick="signupNewsletter(this)" style="background: #222 none repeat scroll 0 0;display: inline;height: 42px;margin-left: 2px;padding: 9px 10px;width: 45px;">
				<img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" width="25" />
			</button>
			<div id="formMessage"></div>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2">
			<ul>
				<h5>Shop</h5>
				<li><a href="https://signup<?php echo $environment; ?>.acorn.tv/createaccount.html">Free Trial Signup</a></li>
				<li><a href="https://store<?php echo $environment; ?>.acorn.tv/#home">Subscription Options</a></li>
				<li><a href="https://store<?php echo $environment; ?>.acorn.tv/#give">Buy A Gift</a></li>
				<li><a href="https://signup<?php echo $environment; ?>.acorn.tv/createaccount-p.html">Redeem A Gift</a></li>
			</ul>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2">
			<ul>
				<h5>Stay Connected</h5>
				<li><a href="https://www.facebook.com/OfficialAcornTV/">Facebook</a>
				</li><li><a href="https://twitter.com/acorntv">Twitter</a></li>
				<li><a href="http://www2.acorn.tv/reviews">Reviews</a></li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
			<ul>
				<h5>Help</h5>
				<li><a href="http://support.acorn.tv/support/solutions/folders/1000220893">FAQs</a>
				</li><li><a href="http://support.acorn.tv/support/home">Help Center</a></li>
				<li><a class="AcornAppLink" data-qs="/help/contactus" href="<?php echo $base_url_path; ?>/contactus">Contact Us</a></li>
			</ul>
		</div>
	</div>
</div>
*/ ?>

<?php do_action( 'rlje_footer_widget_area' ); ?>

<?php do_action( 'rlje_footer_navigation' ); ?>

<?php wp_footer(); ?>
<?php
	$stripe_customer_id = ( ! empty( $_COOKIE['ATVSessionCookie'] ) ) ? rljeApiWP_getStripeCustomerId( $_COOKIE['ATVSessionCookie'] ) : false;
if ( $stripe_customer_id ) :
	?>
<style>
div#the-stunning-bar {
	bottom: 0;
	top: initial;
}
</style>
<script type="text/javascript">
(function(d, t) {
	var e = d.createElement(t),
	s = d.getElementsByTagName(t)[0];
	e.src = 'https://d1gqkepxkcxgvm.cloudfront.net/stunning-bar.js';
	e.id  = 'stunning-bar';
	e.setAttribute('data-app-ckey', '1742pkulzsyysulfkngkfulcd');
	e.setAttribute('data-stripe-id', '<?php echo $stripe_customer_id; ?>');
	s.parentNode.insertBefore(e, s);
}(document, 'script'));
</script>
<?php endif; ?>
</body>
</html>
