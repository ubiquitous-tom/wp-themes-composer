jQuery(document).ready(function($) {
    $('form#signup-step-one').on('submit', function(event) {
        event.preventDefault();
        $('.alert').remove();
        var valid = true;
        var email = $(this).find('input#signup-email').val();
        var email_confirm = $(this).find('input#signup-email-confirm').val();
        var password = $(this).find('input#signup-password').val();
        var password_confirm = $(this).find('input#signup-password-confirm').val()
        if (email !== email_confirm) {
            valid = false;
            var alert = $(document.createElement('div')).addClass( "row alert alert-danger fade in" ).append('<p>Email addresses don\'t match</p>');
            alert.insertAfter($('#progress-steps'));
        }
        if (password !== password_confirm) {
            valid = false;
            var alert = $(document.createElement('div')).addClass( "row alert alert-danger fade in" ).append('<p>Passwords don\'t match</p>');
            alert.insertAfter($('#progress-steps'));
        }
        if(valid) {
            console.log(signup_vars);
            $.post(
                signup_vars.ajax_url,
                {
                    'action': 'user_signup',
                    'email_address': email,
                    'password': password,
                },
                function(response) {
                    if(response.success == false) {
                        var alert = $(document.createElement('div')).addClass( "row alert alert-danger fade in" ).append($(document.createElement('p'))).html(response.error);
                        alert.insertAfter($('#progress-steps'));
                    } else {
                        // Render second step.
                    }
                }
            )
        }
    })
});