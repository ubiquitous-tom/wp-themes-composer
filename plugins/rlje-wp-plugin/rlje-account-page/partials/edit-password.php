<h3 class="section-header">Change Your Password</h3>
<p>If you wish to change your password, Please provide the details below.</p>
<p>
	<small>Please enter a minimum of 6 characters.</small>
</p>
<?php if ( ! empty( $message_error ) ) { ?>
	<p class="bg-danger"><?php echo $message_error; ?></p>
<?php } elseif ( ! empty( $message_sucess ) ) { ?>
	<p class="bg-success"><?php echo $message_sucess; ?></p>
<?php } ?>
<form id="user-change-password" method="POST">
	<div class="form-group">
		<label for="new-password">New Password</label>
		<input id="new-password" class="form-control" name="new-password" type="password" required>
	</div>
	<div class="form-group">
		<label for="new-password-confirm">Confirm New Password</label>
		<input id="new-password-confirm" class="form-control" name="new-password-confirm" type="password" required>
	</div>
	<button type="submit" class="btn btn-primary">Change Password</button>
</form>
