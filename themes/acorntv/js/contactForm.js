jQuery(function($) {
    var $subject = $('#subject');

    $('#issue-select').change(function() {
        $(".issues-list").hide();

        var selectedIssue = $(this).val();
        $("#" + selectedIssue).show();
    });


    //get session Cookie for ID
    var c_name = "ATVSessionCookie";
    var sessionId = document.cookie;
    var c_start = sessionId.indexOf(" " + c_name + "=");
    if (c_start == -1) {
        c_start = sessionId.indexOf(c_name + "=");
    }
    if (c_start == -1) {
        sessionId = null;
    } else {
        c_start = sessionId.indexOf("=", c_start) + 1;
        var c_end = sessionId.indexOf(";", c_start);
        if (c_end == -1) {
            c_end = sessionId.length;
        }

        sessionId = unescape(sessionId.substring(c_start, c_end));
        $("#sessionId").attr('value', sessionId);
    }

    $('#acornDiagnostic').validate({
        rules: {

        },
        messages: {

        },
        submitHandler: function(form) {

            var desc = $.trim($('#Description').val());
            desc = desc ? desc : '<empty>';
			desc = encodeURIComponent(desc);

            $.ajax({
                type: 'POST',
                data: "CookiesEnabled=" + encodeURIComponent($.trim($('#cookiesEnabled').val())) + "&Browser=" + encodeURIComponent($.trim($('#browser').val())) + "&ScreenSize=" + encodeURIComponent($.trim($('#screenSize').val())) + "&ReferringURL=" + encodeURIComponent($.trim($('#referringUrl').val())) + "&FlashPlayer=" + encodeURIComponent($.trim($('#flashPlayer').val())) + "&Description=" + encodeURIComponent($.trim(desc)) + "&UserAgent=" + encodeURIComponent($.trim($('#userAgentHeader').val())) + "&Title=" + encodeURIComponent($.trim($('#subject').val())) + "&Model=" + encodeURIComponent($.trim($('#Model').val())) + "&ConnectionSpeed=" + encodeURIComponent($.trim($('#connSpeed').val())) + "&Email=" + encodeURIComponent($.trim($('#email').val())),
                url: '/contactus',
                dataType: "text",
                success: function(data) {
                    $('#diagnostic').remove();
                    $('#msg').html("<div class=\"alert alert-success\"><h4>Thank you for contacting Acorn TV.</h4>We’ve received your email. An Acorn TV support representative will review your request and send you a personal response. Be sure to visit the <a href=\"http://support.acorn.tv/support/home\">Acorn TV Help Center</a> for more information and solutions to common issues.</div>");
                },
                error: function(request, error) {
                    $("#msg").html("<div class=\"alert alert-error\">There was a problem with your submission, please try again.</div>");
                }
            });

            return false;
        }
    });
})