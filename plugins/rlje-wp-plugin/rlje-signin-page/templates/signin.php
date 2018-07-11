<?php
get_header();
?>
<section class="home-signin">
    <div id="page-wrap">
        <h4>Welcome to <?php bloginfo("name") ?></h4>
        <h5>Please use your current <?php bloginfo("name") ?> e-mail and password.</h5>
        <?php
            if(isset($message)) { ?>
            <section><?php echo $message ?></section>
            <? }
        ?>
        <div class="login-form">
            <form id="signin" class="form-signin" method="post">
                <h3>Sign In</h3>
                <div class="control-group">
                    <label for="email">E-Mail</label>
                    <input required name="user_email" type="email">
                </div>
                <div class="control-group">
                    <label for="password">Password<small>(case-sensitive)</small></label>
                    <input id="password" name="user_password" type="password">
                </div>
                <button class="btn btn-primary">Sign In</button>
                <p>
                    <a href="<?php echo home_url('forgotpassword') ?>">Forgot your password?</a>
                </p>
            </form>
        </div>
    </div>
</section>
<?php
get_footer();