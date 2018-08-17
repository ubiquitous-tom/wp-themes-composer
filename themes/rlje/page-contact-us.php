<?php
get_header();
?>

<section id="contact-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3>Contact Us</h3>
			</div>
		</div>
	</div>
</section>
<section id="contact-form">
	<div class="container browse">
		<div id="contactus-view" class="url-view">

			<div id="msg"></div>

			<form method="post" id="contact-us">

				<p>
					If you’re having trouble with watching films, signing up, or any difficulty with using the site, please see our <a href="https://support.umc.tv">support page</a> for assistance. Many common solutions are provided on our <a href="http://support.acorn.tv/support/solutions/folders/1000220893"> Frequently Asked Questions</a> page. For all other inquiries, please use the form below and we’ll get in touch.
				</p>

				<div class="form-group">
					<label for="full-name">Name</label>
					<input type="text" id="full-name" class="form-control" required>
				</div>

				<div class="form-group">
					<label for="email">E-Mail Address</label>
					<input type="email" id="email" name="email" class="form-control" required>
				</div>

				<div class="form-group">
					<label for="subject">Subject</label>
					<input type="text" id="subject" name="subject" class="form-control" required>
				</div>

				<div class="form-group">
					<label for="description">Message</label>
					<textarea id="description" title="" name="description" rows="6" class="form-control" required></textarea>
				</div>

				<button class="btn btn-primary btn-large btn-block">Submit</button>

			</form>

		</div>
	</div>
</section>

<?php
get_footer();
