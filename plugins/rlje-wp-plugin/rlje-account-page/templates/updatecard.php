<?php
get_header();
$stripe_id = $this->user_profile['Customer']['StripeCustomerID'];
$stunning_token = '2047dxrbmvipxdnrcfbetfxoe';
$stunning_url = 'https://payments.stunning.co/payment_update/' . $stunning_token . '/' . $stripe_id;
?>
<iframe id="stunningframe" sandbox="allow-forms allow-scripts allow-same-origin" src="<?php echo $stunning_url; ?>">
</iframe>

<?php
get_footer();