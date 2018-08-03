(function($) {
  'use strict';

  $(document).ready(function() {
    $('.sortable').sortable({
      connectWith: '.draggable',
      items: 'li:not(.header)',
      // placeholder: 'ui-state-highlight',
      revert: true,
      over: function(event, ui) {
        // console.log('over',event, ui);
        $(ui.item).css({ width: '48%' });
      },
      update: function(event, ui) {
        // console.log('update',event, ui);
        $(ui.item).css({ width: '90%' });
        var area_one = $('#area-one').sortable('toArray');
        var area_two = $('#area-two').sortable('toArray');
        console.log(area_one, area_two);
        $('input.area-one').val(area_one);
        $('input.area-two').val(area_two);
      }
    });

    $('.draggable').draggable({
      cancel: '.header',
      connectToSortable: '.sortable',
      connectWith: '.sortable',
      container: '#drag-n-drop-section',
      // placeholder: 'ui-state-highlight',
      revert: 'invalid',
      stop: function(event, ui) {
        console.log('stop', event, ui);
        $(ui.item).css({ width: '200px' });
      }
    });

    // $( '.sortable' ).droppable({
    //   accept: '.draggable',
    //   classes: {
    //     'ui-droppable-active': 'ui-state-active',
    //     'ui-droppable-hover': 'ui-state-hover'
    //   },
    //   drop: function( event, ui ) {
    //     $( this ).addClass( 'ui-state-highlight' );
    //   }
    // });
  });
})(jQuery);
