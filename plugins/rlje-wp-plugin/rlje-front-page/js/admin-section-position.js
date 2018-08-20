(function($) {
  'use strict';

  $(document).ready(function() {
    $('#homepage-layout').sortable({
      items: 'li:not(.disabled)',
      cancel: '.categories-item',
      update: function(event, ui) {
        // console.log('update', event, ui);
        var layout = $(this).sortable('toArray');
        // console.log(layout);
        $('input#section-position-layout').val(layout);
      }
    });
  });
})(jQuery);
