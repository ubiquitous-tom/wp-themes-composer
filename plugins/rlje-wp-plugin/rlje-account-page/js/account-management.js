jQuery(document).ready(function($) {
    $('button#confirmCancel').on('click', function() {
        var btn = $(this);
        // Disable the button and show an indicator that we are cancelling
        btn.prop('disabled', true);
        btn.text('cancelling ...');
        // Hit the API to cancel user subscription
        console.log(account_management_vars);
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
    })
});