<?php 
get_header();
?>
<section id="account-settings">
    <div class="container">
        <nav class="col-md-3">
            <div class="account-nav">
                <ul class="nav nav-tabs nav-stacked">
                    <li><a href="<?php echo esc_url( home_url("account/status") ); ?>">Account Status</a></li>
                    <?php if($this->user_profile['Membership']['WebPaymentEdit']) { ?>
                    <li><a href="<?php echo esc_url( home_url("account/editEmail")) ?>">Change e-mail</a></li>
                    <?php } ?>
                    <li><a href="<?php echo esc_url( home_url("account/editPassword")) ?>">Change Password</a></li>
                    <?php if($this->user_profile['Membership']['WebPaymentEdit']) { ?>
                    <li><a href="<?php echo esc_url( home_url("account/editBilling")) ?>">Update Billing Info</a></li>
                    <li><a href="<?php echo esc_url( home_url("account/cancelMembership")) ?>">Cancel Membership</a></li>
                    <li><a href="<?php echo esc_url( home_url("account/applyCode")) ?>">Apply Code</a></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <section class="col-md-9">
        <?php $partial = $this->show_subsection();
            require ($partial);
        ?>
        </section>
    </div>
    
</section>


<?php
get_footer();