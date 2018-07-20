<?php
get_header();
$stripe_id = $this->user_profile['Customer']['StripeCustomerID'];
$stunning_token = '1742pkulzsyysulfkngkfulcd';
$stunning_url = 'https://payments.stunning.co/payment_update/' . $stunning_token . '/' . $stripe_id;
?>
<iframe id="stunningframe" sandbox="allow-forms" src="<?php echo $stunning_url; ?>">
</iframe>

<?php
get_footer();