<?php
get_header();
?>

<section class="page-body">
	<div class="container">
		<header>
			<h2>Give <?php bloginfo( 'name' ); ?></h2>
			<h3>Share the he best in Black films &amp; television with someone special</h3>
			<p>Your gift purchase entitles the recipient to a one year <?php bloginfo( 'name' ); ?> membership. They'll enjoy hundreds of the best dramas, and comedies—all in one place, always available, and always commercial-free.</p>
			<p>Your purchase also includes a printer-friendly gift certificate with redemption instructions - perfect for adding to a card or a stocking!</p>
		</header>
		<div class="gift-section row">
			<div class="col-md-4">
				<i class="fa fa-gift" aria-hidden="true"></i>
			</div>
			<div class="col-md-4">
				<h3><?php bloginfo( 'name' ); ?> Gift Membership</h3>
				<p>Give 12 months of unlimited access to the best in black films &amp; TV</p>
			</div>
			<div class="col-md-4">
				<input id="gift-quantity" value="1" type="number" min="1" max="3" /> X $<span id="membership-cost"></span>
			</div>
		</div>
		<div class="checkout-total row">
			<div class="col-md-4">
				<strong>Total: $<span id="total"></span></strong>
			</div>
			<div class="col-md-4">
				<button class="btn btn-primary btn-lg">Checkout</button>
			</div>
		</div>
	</div>
	<script type="text/x-tmpl" id="tmpl-demo">
		<div class="container">
			<div class="row">
				<header class="col-md-8">
					<h2>UMC Checkout</h2>
					<h3>Billing information</h3>
					<p>To make your purchase, please fill in the form below.</p>
				</header>
				<div class="order-summary col-md-4">
					<h4>Order Summary</h4>
					<p>UMC Gift Membership: {%=o.quantity%} X ${%=o.cost%}</p>
					<p>Total: {%= o.quantity * o.cost %}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
					<form id="purchase-gift" action="">
						<h4>Billing information</h4>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="billing-first-name">First Name *</label>
								<input id="billing-first-name" class="form-control" type="text" required>
							</div>
							<div class="form-group col-md-6">
								<label for="billing-last-name">Last Name *</label>
								<input id="billing-last-name" class="form-control" type="text" required>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="billing-country">Country *</label>
								<select id="billing-country" class="form-control" disabled>
									<option value="US">United States</option>
								</select>
							</div>
							<div class="form-group col-md-6">
								<label for="billing-zip">Zip / Postal Code *</label>
								<input id="billing-zip" class="form-control" type="text" required>
							</div>
						</div>
						<div class="form-group">
							<label for="billing-email">Email Address *</label>
							<input id="billing-email" class="form-control" type="email" min="6" required>
						</div>
						<div class="form-group">
							<label for="billing-email-confirm">Confirm Email Address *</label>
							<input id="billing-email-confirm" class="form-control" type="email" min="6" required>
						</div>
						<h4>Credit Card Information</h4>
						<div class="form-group">
							<label for="card-name">Name on Card *</label>
							<input id="card-name" class="form-control" type="text" required>
						</div>
						<div class="form-group">
							<label for="card-number">Card Number *</label>
							<div id="card-number"></div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label for="card-expiration">Expiration *</label>
								<div id="card-expiration"></div>
							</div>
							<div class="form-group col-md-6">
								<label for="card-cvc">CVC *</label>
								<div id="card-cvc"></div>
							</div>
						</div>
						<button class="submit btn btn-primary btn-lg">Review &amp; Place Order</button>
					</form>
				</div>
				<div class="faqs col-md-4 well">
					<h4>Frequently Asked Questions</h4>
					<p>
						<strong>How do I watch UMC</strong><br>
						All you need is an Interned connection and a device to watch on.
					</p>
					<p>
						<strong>When do you add new shows?</strong><br>
						UMC adds new shows every week.
					</p>
					<p>
						<strong>Are there closed captions or subtitles available on Acorn TV programs?</strong><br>
						Yes! All of our programs include closed captions and/or SDH subtitles.
					</p>
					<p>For more FAQs, <a href="/faqs">CLICK HERE</a></p>
				</div>
			</div>
		</div>
	</script>
</section>

<?php
get_footer();
