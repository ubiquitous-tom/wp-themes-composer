<h3>My Account</h3>
<h4>Account Status</h4>
<ul>
	<li class="row">
		<div class="col-sm-4 text-right">Membership Type</div>
		<div class="col-sm-8"><strong>Standard</strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Membership Status</div>
		<div class="col-sm-8"><strong><?php echo ucfirst( strtolower( $this->user_profile['Membership']['Status'] ) ); ?></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Membership Term</div>
		<div class="col-sm-8"><strong><?php echo ucfirst( $this->get_user_term() ); ?></strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Next Billing Date</div>
		<div class="col-sm-8"><strong><?php echo $this->get_next_billing_date(); ?></strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Amount to be Charged</div>
		<div class="col-sm-8"><strong><?php echo $this->get_next_billing_amount(); ?></strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Name</div>
		<div class="col-sm-8"><strong><?php echo $this->get_user_name(); ?></strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">E-Mail</div>
		<div class="col-sm-8"><strong><?php echo $this->get_user_email(); ?></strong></div>
	</li>
	<li class="row">
		<div class="col-sm-4 text-right">Join Date</div>
		<div class="col-sm-8"><strong><?php echo $this->get_user_join_date(); ?></strong></div>
	</li>
	<?php
	// When WebPaymentEdit come in false, We should show a field reminding user their peyment is being
	// managed by a other company.
	if ( $this->user_profile['Membership']['WebPaymentEdit'] === false ) {
		?>
		<li>Payment Method: <strong>You purchased your membership through <?php echo $this->user_profile['Membership']['Device']; ?>. Please log in to <?php $this->user_profile['Membership']['Device']; ?> to manage your billing details or cancel your account.</strong></li>
	<?php } ?>

</ul>
