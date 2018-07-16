<h3>My Account</h3>
<h4>Account Status</h4>
<ul>
    <li>Membership Type: <strong>Standard</strong></li>
    <li>Membership Status: <strong><?php echo ucfirst(strtolower($this->user_profile['Membership']['Status'])); ?></strong></li>
    <li>Membership Term: <strong><?php echo ucfirst($this->get_user_term()); ?></strong></li>
    <li>Next Billing Date: <strong><?php echo $this->get_next_billing_date(); ?></strong></li>
    <li>Amount to be Charged: <strong><?php echo $this->get_next_billing_amount(); ?></strong></li>
    <li>Name: <strong><?php echo $this->get_user_name(); ?></strong></li>
    <li>E-Mail: <strong><?php echo $this->get_user_email(); ?></strong></li>
    <li>Join Date: <strong><?php echo $this->get_user_join_date(); ?></strong></li>
    <?php
    // When WebPaymentEdit come in false, We should show a field reminding user their peyment is being
    // managed by a other company.
    if($this->user_profile['Membership']['WebPaymentEdit'] === false) { ?>
        <li>Payment Method: <strong>You purchased your membership through <?php echo $this->user_profile['Membership']['Device'] ?>. Please log in to <?php $this->user_profile['Membership']['Device'] ?> to manage your billing details or cancel your account.</strong></li>
    <?php } ?>

</ul>