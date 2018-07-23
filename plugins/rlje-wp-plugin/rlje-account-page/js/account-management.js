jQuery(document).ready(function($) {
    $('button#confirmCancel').on('click', function() {
        var btn = $(this);
        // Disable the button and show an indicator that we are cancelling
        btn.prop('disabled', true);
        btn.text('cancelling ...');
        // Hit the API to cancel user subscription
        $.post(
            account_management_vars.ajax_url,
            {
                'action': 'cancel_sub',
                'session_id': account_management_vars.session_id
            },
            function(response) {
                $('#cancelMembershipModal').modal('hide');
                var alert = $(document.createElement('div')).addClass( "alert fade in" );
                if(response.success) {
                    alert.addClass("alert-success");
                    alert.append("<p>Your subscription has been cancelled.</p>");
                } else {
                    alert.addClass("alert-danger");
                    alert.append("<p>There was an error cancelling your account. We've been notified and working to fix it.</p>");
                }
                $("#account-settings section").prepend(alert);
            }
        );
    });

    $('form#apply-code').on('submit', function(event) {
        event.preventDefault();
        var promoCode = $( this ).find('[name=promo-code]').val();
        var btn = $(this).find('button#apply-promo-code');
        // Disable the button and show an indicator that we are cancelling
        btn.prop('disabled', true);
        btn.text('Applying code ...');
        $.post(
            account_management_vars.ajax_url,
            {
                'action': 'apply_promo_code',
                'session_id': account_management_vars.session_id,
                'promo_code': promoCode,
            },
            function(response) {
                var alert = $(document.createElement('div')).addClass( "alert fade in" );
                if(response.success) {
                    alert.addClass("alert-success");
                    alert.append("<p>Promo code has been applied.</p>");
                } else if (response.error) {
                    alert.addClass("alert-danger");
                    alert.append($(document.createElement('p')).html( response.error ));
                } else {
                    alert.addClass("alert-danger");
                    alert.append("<p>Invalid promo code.</p>");
                }
                $("#account-settings section").prepend(alert);
                btn.prop('disabled', false);
                btn.text('Apply Code');
            }
        )
    })
});