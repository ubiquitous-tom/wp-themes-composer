var sessionId;
var stripe;
var card;

function initializeStripeElements(stripeKey) {
    stripe = Stripe(stripeKey);
    var style = {
        base: {
            lineHeight: '20px',
            fontFamily: 'Avenir, Nunito, sans-serif',
            fontSize: '14px',
        }
    }
    var elements = stripe.elements();

    card = elements.create('card', { hidePostalCode: true, style: style });
    card.mount('#card-element');

    card.addEventListener('change', function (event) {
        console.log(event);
    })

}

function renderStripeInput($) {
    var card_label = $(document.createElement('label')).attr('for', 'card-element').html('Credit or debit card');
    var card_container = $(document.createElement('div')).attr('id', 'card-element');
    var card_group = $(document.createElement('div')).addClass('form-group').append(card_label, card_container);
    return card_group;
}

function showStepTwo($, signup_form) {
    // Render second step.
    // Update header and side description of the page
    $('#signup h3').html('Last step and then start watching');
    $('#signup p.side').html('Watch free for 7 days. Just $4.99/month after that. No commitment: cancel within 7 days to avoid payment.');
    // Attach new handlers to handle form submit event
    signup_form.off('submit');
    // Update the form fields
    signup_form.empty();

    var first_name_label = $(document.createElement('label')).attr('for', 'user-first-name').html('First Name *');
    var first_name_input = $(document.createElement('input')).addClass('form-control').attr(
        {
            id: 'user-first-name',
            name: 'user_first_name',
            type: 'text'
        }
    ).prop('required', true);
    var first_name_group = $(document.createElement('div')).addClass('form-group').append(first_name_label, first_name_input);

    var last_name_label = $(document.createElement('label')).html('Last Name *');
    var last_name_input = $(document.createElement('input')).addClass('form-control').attr(
        {
            id: 'user-last-name',
            name: 'user_last_name',
            type: 'text'
        }
    ).prop('required', true);
    var last_name_group = $(document.createElement('div')).addClass('form-group').append(last_name_label, last_name_input);

    var countries = [
        $(document.createElement('option')).attr('value', 'US').html('United States'),
        $(document.createElement('option')).attr('value', 'CA').html('Canada'),
        $(document.createElement('option')).attr('value', 'AS').html('American Samoa'),
        $(document.createElement('option')).attr('value', 'AR').html('Argentina')
    ]
    var country_label = $(document.createElement('label')).html('Country *');
    var country_select = $(document.createElement('select')).addClass('form-control').append(countries);
    var country_group = $(document.createElement('div')).addClass('form-group').append(country_label, country_select);

    // Name on card field
    var card_name_label = $(document.createElement('label')).attr('for', 'card-name').html('Name on Card *');
    var card_name_input = $(document.createElement('input')).addClass('form-control').attr({
        id: 'card-name',
        name: 'card_name',
        type: 'text'
    }).prop('required', true);
    var card_name_group = $(document.createElement('div')).addClass('form-group').append(card_name_label, card_name_input);

    // Card number input elements for strip js to mount to
    var stripe_group = renderStripeInput($);

    // Submit button
    var step_two_submit = $(document.createElement('button')).addClass('btn btn-primary btn-lg center-block').html('Signup');

    signup_form.append(first_name_group, last_name_group, country_group, card_name_group, stripe_group, step_two_submit);

    // Initialize Stripe so it can mount it's iframes
    initializeStripeElements(signup_vars.stripe_key);

    // Attach form submit handler
    signup_form.on('submit', submitStepTwo);
}

function submitStepTwo(event) {
    // We have an initialized 
    event.preventDefault();
    stripe.createToken(card).then(function (result) {
        if (result.error) {
            console.log(result.error.message);
        } else {
            var stripe_token = result.token;
            var billing_first_name = jQuery('input#user-first-name').val();
            var billing_last_name = jQuery('input#user-last-name').val();
            var name_on_card = jQuery('input#card-name').val();
            jQuery.post(
                signup_vars.ajax_url,
                {
                    'action': 'create_membership',
                    'session_id': sessionId,
                    'billing_first_name': billing_first_name,
                    'billing_last_name': billing_last_name,
                    'name_on_card': name_on_card,
                    'stripe_token': stripe_token.id
                },
                function (response) {
                    if (response.success == false) {
                        var alert = jQuery(document.createElement('div')).addClass("row alert alert-danger fade in").append(jQuery(document.createElement('p'))).html(response.error);
                        alert.insertAfter(jQuery('#progress-steps'));
                    } else {
                        showStepThree();
                    }
                }
            )
        }
    });

}

function showStepThree() {
    // Render last step.
    // Update header and side description of the page
    jQuery('#signup h3').html('Congratulations! Sign up complete.');
    jQuery('#signup p.side').html('Thanks for trying UMC! We think you\'ll love it.');
    // Get rid of the form.
    jQuery('form.signup').remove();
}

jQuery(document).ready(function ($) {
    $('form.signup').on('submit', function (event) {
        event.preventDefault();
        $('.alert').remove();
        var valid = true;
        var signup_form = $(this);
        var email = $(this).find('input#signup-email').val();
        var email_confirm = $(this).find('input#signup-email-confirm').val();
        var password = $(this).find('input#signup-password').val();
        var password_confirm = $(this).find('input#signup-password-confirm').val()
        if (email !== email_confirm) {
            valid = false;
            var alert = $(document.createElement('div')).addClass("row alert alert-danger fade in").append('<p>Email addresses don\'t match</p>');
            alert.insertAfter($('#progress-steps'));
        }
        if (password !== password_confirm) {
            valid = false;
            var alert = $(document.createElement('div')).addClass("row alert alert-danger fade in").append('<p>Passwords don\'t match</p>');
            alert.insertAfter($('#progress-steps'));
        }
        if (valid) {
            $.post(
                signup_vars.ajax_url,
                {
                    'action': 'initialize_account',
                    'email_address': email,
                    'password': password,
                },
                function (response) {
                    if (response.success == false) {
                        var alert = $(document.createElement('div')).addClass("row alert alert-danger fade in").append($(document.createElement('p'))).html(response.error);
                        alert.insertAfter($('#progress-steps'));
                    } else {
                        // Update the form to show step two fields
                        sessionId = response.session_id;
                        showStepTwo($, signup_form);
                    }
                }
            )
        }
    })
});