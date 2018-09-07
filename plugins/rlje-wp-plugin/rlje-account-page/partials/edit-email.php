<h3 class="section-header">Change Your E-Mail</h3>
<p>If you wish to change your password, please provide the details below.</p>
<?php if ( ! empty( $message_error ) ) { ?>
	<p class="bg-success"><?php echo $message_error; ?></p>
<?php } elseif ( ! empty( $message_sucess ) ) { ?>
	<p class="bg-success"><?php echo $message_sucess; ?></p>
<?php } ?>

<form id="user-signin" method="POST">
	<div class="form-group">
		<label for="new-email">E-Mail</label>
		<input id="new-email" class="form-control" name="new-email" type="email" required>
	</div>
	<div class="form-group">
		<label for="new-email-confirm">Confirm E-Mail</label>
		<input id="new-email-confirm" class="form-control" name="new-email-confirm" type="email" required>
	</div>
	<button type="submit" class="btn btn-primary">Change E-Mail</button>
</form>
