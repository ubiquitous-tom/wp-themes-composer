<?php 
get_header();
?>
<section id="account-settings">
    <div class="container">
        <nav class="col-md-3">
            <div class="account-nav">
                <ul class="nav nav-tabs nav-stacked">
                    <li>Account Status</li>
                    <?php if($this->user_profile['Membership']['WebPaymentEdit']) { ?>
                    <li>Change e-mail</li>
                    <?php } ?>
                    <li>Change Password</li>
                    <?php if($this->user_profile['Membership']['WebPaymentEdit']) { ?>
                    <li>Update Billing Info</li>
                    <li>Cancel Membership</li>
                    <li>Apply Code</li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <section class="col-md-9">
        <?php do_action( 'rlje_user_settings_page' ); ?>
        </section>
    </div>
    
</section>


<?php
get_footer();