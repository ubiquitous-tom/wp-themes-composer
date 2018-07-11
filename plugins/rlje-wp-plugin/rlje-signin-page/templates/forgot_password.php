<?php
// TODO: replace the hardcoded support email address with one coming from wp_settings
get_header();
?>
<section class="home-signin">
    <div id="page-wrap">
        <h4>Reset Your Password</h4>
        <?php
            if(isset($password_reset_failed) && $password_reset_failed === false) { ?>
            <p>We have sent you an email with a link you can use to create a new password. If you do not see the email please check your promotions and spam folders.</p>
            <p>If the email is not in any of these locations, please <a href="<?php echo home_url('contactus') ?>">Contact Us</a>.</p>
        <?php
            } else {
                if(isset($password_reset_failed) && $password_reset_failed === true) { ?>
                    <section>
                        <h4>Error Resetting Your Password</h4>
                        <p>Your e-mail address was not found, please check it and try again.</p>
                    </section>
                <?php } ?>
                <p>Please enter the email address you used to create your account. Within a few minutes, we will send you a link so you can create a new password.</p>
                <p>Please ensure that <strong>support@umc.tv</strong> is in your allowed senders list or else the reset link might end up in your spam folder.</p>
                <div class="password-rest-form">
                    <form class="password-reset" method="post">
                        <div class="control-group">
                            <label for="email">Your email Address</label>
                            <input required id="email" name="user_email" type="email">
                        </div>
                        <button class="btn btn-primary">Send me a reset link</button>
                    </form>
                </div>

            <? }
        ?>
    </div>
</section>
<?php
get_footer();