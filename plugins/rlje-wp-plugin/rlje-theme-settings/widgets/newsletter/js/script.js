(function($) {
	'use strict';

	$('#signup-newsletter-widget form').on('submit', function(e) {
		e.preventDefault();
		var $emailInput = $('#signup-newsletter-email'),
		$emailButtonInput = $('#signup-newsletter-button'),
		email = $.trim($emailInput.val()),
		messageDiv = $('<div>').addClass('alert'),
		$message = $('#signup-newsletter-message');

		$.ajax({
			type: 'POST',
			url: rlje_newsletter_widget_object.ajax_url,
			data: {
				'action' : 'rlje_newsletter_signup',
				'email': email,
				'token': rlje_newsletter_widget_object.token
			},
			success: function(resp) {
				var data = resp.data;
				$message.append(messageDiv.addClass(data.type).html(data.message));
				$emailInput.prop('disabled', true);
				$emailButtonInput.prop('disabled', true);
			},
			error: function(error) {
				var data = error.data;
				$message.append(messageDiv.addClass(data.type).html(data.message));
			}
		});
	});
	// var signupNewsletter = function(el) {
	// 	var re = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,
	// 		$signupNewsletter = $(el).parent(),
	// 		$signupEmail = $signupNewsletter.find('#signupEmail').val();

	// 	if (re.test($signupEmail.trim())) {
	// 		$.ajax({
	// 			type: 'POST',
	// 			data: "SignupEmail=" + $signupEmail,
	// 			url: '/signupnewsletter',
	// 			dataType: "text",
	// 			success: function() {
	// 				$signupNewsletter.find('#formMessage').html('<div class="alert alert-success">Thank you for subscribing!</div>');
	// 			},
	// 			error: function() {
	// 				$signupNewsletter.find('#formMessage').html('<div class="alert alert-error">There was a problem with your submission, please try again.</div>');
	// 			}
	// 		});
	// 	}
	// 	else {
	// 		$signupNewsletter.find('#formMessage').html('<div class="alert alert-error">Invalid email address.</div>');
	// 	}
	// };
})(jQuery);
