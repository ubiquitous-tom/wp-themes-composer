(function($, window) {
  $(document).ready(function() {
    // Change Locale from Footer
    $('#atv-locale').on('change', function() {
      var locale = $(this).val();
      var data = {
        'action': 'set_locale',
        'nonce': rlje_locale_object.nonce,
        'rlje_locale': locale,
        'location_pathname': window.location.pathname
      };
      $.post(rlje_locale_object.ajax_url, data)
        .done(function(resp) {
          var data = resp.data;
          docCookies.setItem('ATVLocale', locale, 3600 * 1000 * 24 * 365, '/', data.cookie_domain);
          window.location = data.redirectTo;
        })
        .fail(function() {
          console.error('Error redefining Locale');
        });
    });
  });
})(jQuery, window);
