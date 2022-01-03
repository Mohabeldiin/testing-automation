(function ($) {
	"use strict";

	jQuery(window).on('load', function() {
		if ($("#myhome_attributes_box").length > 0 && $("#acf-myhome_estate .inside.acf-fields").length > 0) {
			if (!$('.acf-tab-group li').first().hasClass('active') && $('.acf-tab-group li.active').length > 0) {
				$("#mh-admin-attributes .acf-field").addClass('acf-hidden');
			}
			$("#acf-myhome_estate .acf-field-myhome-estate-tab-general").after($("#mh-admin-attributes").html());
			$("#myhome_attributes_box").remove();
		}


		$('.selectize').selectize({
			sortField: 'text',
			create   : function (input) {
				return {
					value: input,
					text : input
				}
			}
		});
	})

	if ($("#myhome_attributes_box").length > 0 && $("#acf-myhome_estate .inside.acf-fields").length > 0) {
		if (!$('.acf-tab-group li').first().hasClass('active') && $('.acf-tab-group li.active').length > 0) {
			$("#mh-admin-attributes .acf-field").addClass('acf-hidden');
		}
		$("#acf-myhome_estate .acf-field-myhome-estate-tab-general").after($("#mh-admin-attributes").html());
		$("#myhome_attributes_box").remove();
	}

	// $('.selectize').selectize({
	// 	sortField: 'text',
	// 	create   : function (input) {
	// 		return {
	// 			value: input,
	// 			text : input
	// 		}
	// 	}
	// });

	$('.selectize-idx').selectize({
		sortField: 'text'
	});

	if ($('.mh-dismiss-yoast-notice').length) {
		var noticeInterval = setInterval(function () {
			if ($('.mh-dismiss-yoast-notice .notice-dismiss').length) {
				$('.mh-dismiss-yoast-notice .notice-dismiss').on('click', function () {
					$.post(ajaxurl, {
						action: 'myhome_yoast_dismiss_notice'
					}, function (response) {

					});
				});
				clearInterval(noticeInterval);
			}
		}, 500);
	}

	if ($(".redux-action_bar").length > 0) {
		$("#redux_save").after($("#myhome-clear-cache"));
		$("#myhome-clear-cache").show();
	}

	jQuery('.plugin-update-tr:not([data-slug]):not([data-plugin]):not([id])').hide();

	window.onload = function () {
		if ($('#acf-myhome_estate_location').length) {
			var disableAutoComplete = false;
			$('#acf-myhome_estate_location .title').after('<div><input type="checkbox" id="myhome-disable-autocomplete"> ' + window.MyHomeAdmin.disable_map_autocomplete + '</div>');
			$('#myhome-disable-autocomplete').bind('change', function () {
				disableAutoComplete = $(this).is(':checked');
			});


			var locationField = acf.getField('myhome_estate_location');
			locationField.searchPosition = function (lat, lng) {
				// vars
				var latLng = this.newLatLng(lat, lng);
				var $wrap = this.$control();

				// set position
				this.setPosition(lat, lng);

				// add class
				$wrap.addClass('-loading');

				// callback
				var callback = $.proxy(function (results, status) {

					// remove class
					$wrap.removeClass('-loading');

					// vars
					var address = '';

					if (!disableAutoComplete) {
						// validate
						if (status != google.maps.GeocoderStatus.OK) {
							console.log('Geocoder failed due to: ' + status);
						} else if (!results[0]) {
							console.log('No results found');
						} else {
							address = results[0].formatted_address;
						}
					} else {
						var val = this.getValue();
						address = val.address;
					}

					// update val
					this.val({
						lat    : lat,
						lng    : lng,
						address: address
					});

				}, this);
				// query
				var googleApiInterval = setInterval(function () {
					if (!google.maps) {
						return;
					}
					clearInterval(googleApiInterval);
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({'latLng': latLng}, callback);
				}, 300);
			};
		}
	}
})(jQuery);