(function( $ ) {
  "use strict";

  window.VcMhCarouselClientsView = vc.shortcode_view.extend({
    events: {
      'click > .vc_controls .column_delete':'deleteShortcode',
      'click > .vc_controls .column_add':'addElement',
      'click > .vc_controls .column_edit':'editElement',
      'click > .vc_controls .column_clone':'clone',
      'mousemove': 'checkControlsPosition'
    }
  });
} )( jQuery );
