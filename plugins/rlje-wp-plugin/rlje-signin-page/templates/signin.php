<?php
get_header();
?>
<section id="signin">
    <div class="container">
        <h4 class="text-center">Welcome to <?php bloginfo("name") ?></h4>
        <p class="text-center">Please use your current <?php bloginfo("name") ?> e-mail and password.</p>
        <?php
            if(isset($message)) { ?>
            <section><?php echo $message ?></section>
            <? }
        ?>
        <div class="login-form row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <form class="signin" method="post">
                    <!-- <h3>Sign In</h3> -->
                    <div class="form-group">
                        <label for="login-email">E-Mail</label>
                        <input id="login-email" class="form-control" required name="user_email" type="email">
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password<small>case-sensitive</small></label>
                        <input id="login-password" class="form-control" name="user_password" type="password">
                    </div>
                    <button class="btn btn-primary center-block">Sign In</button>
                    <p class="text-center">
                        <a href="<?php echo home_url('forgotpassword') ?>">Forgot your password?</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();