jQuery(function($) {

    $('form#contact-us').on('submit', function(event) {
        event.preventDefault();
        console.log('Submitted!');
        var name = $(this).find('input#full-name').val();
        var email_address = $(this).find('input#email').val();
        var subject = $(this).find('input#subject').val();
        var desc = $(this).find('textarea#description').val();
        $.post(
            contact_vars.ajax_url,
            {
                'action': 'process_contact_us',
                'name': name,
                'email': email_address,
                'subject': subject,
                'desc': desc
            },
            function (response) {
                if (response.success == false) {
                    $("#msg").html("<div class=\"alert alert-error\">There was a problem with your submission, please try again.</div>");
                } else {
                    $('#msg').html("<div class=\"alert alert-success\"><h4>Thank you for contacting UMC.</h4>We’ve received your email. A UMC support representative will review your request and send you a personal response. Be sure to visit the <a href=\"http://support.umc.tv/support/home\">UMC Help Center</a> for more information and solutions to common issues.</div>");
                }
            }
        )
    })
})