jQuery(function($) {

    $('form#customer-support').on('submit', function(event) {
        event.preventDefault();
        console.log('Submitted!');
        var name = $(this).find('input#full-name').val();
        var email_address = $(this).find('input#email').val();
        var support_topic = $(this).find('select#topic').val();
        var device_type = $(this).find('input#device-type').val();
        var device_model = $(this).find('input#device-model').val();
        var browser_version = $(this).find('input#browser-version').val();
        var desc = $(this).find('textarea#description').val();
        $.post(
            local_vars.ajax_url,
            {
                'action': 'process_customer_support',
                'name': name,
                'email': email_address,
                'topic': support_topic,
                'device_type': device_type,
                'device_model': device_model,
                'browser': browser_version,
                'desc': desc
            },
            function (response) {
                if (response.success == false) {
                    $("#msg").html("<div class=\"alert alert-error\">There was a problem with your submission, please try again.</div>");
                } else {
                    $('#msg').html("<div class=\"alert alert-success\"><h4>Thank you for submitting your help request! Your information has been passed to our Help system, and you will receive a confirmation email with a link to your ticket.</h4></div>");
                }
            }
        )
    })
})