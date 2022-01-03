(function ($) {
	"use strict";

	var galleryTop = new Swiper('.swiper-container--single', {
		autoHeight  : true,
		loop        : true,
		spaceBetween: 0,
		speed       : 500,
		pagination  : {
			el  : '.swiper-pagination',
			type: 'fraction',
		},
		navigation  : {
			nextEl   : '.swiper-button-next',
			prevEl   : '.swiper-button-prev',
			clickable: true,
		},
		loopedSlides: $('.swiper-container--single .swiper-slide').length,
		on          : {
			init: function () {
				if ($('a.mh-popup-group__element').length) {
					$('.swiper-slide:not(.swiper-slide-duplicate) a.mh-popup-group__element').magnificPopup({
						type           : 'image',
						tLoading       : 'Loading image #%curr%...',
						mainClass      : 'mfp-no-margins mfp-with-zoom',
						fixedContentPos: false,
						gallery        : {
							enabled           : true,
							navigateByImgClick: true,
							preload           : [0, 1] // Will preload 0 - before current, and 1 after the current image
						},
						image          : {
							tError     : '',
							verticalFit: true
						}
					});
				}
			},
		},
	});

	var galleryThumbs = new Swiper('.swiper-container--single-thumbs', {
		loop               : true,
		speed              : 500,
		spaceBetween       : 0,
		centeredSlides     : true,
		slidesPerView      : 'auto',
		slideToClickedSlide: true
	});
	galleryTop.controller.control = galleryThumbs;
	galleryThumbs.controller.control = galleryTop;

})(jQuery);
