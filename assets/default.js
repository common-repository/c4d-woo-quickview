(function($){
	"use strict";
	if (typeof c4d_woo_qv == 'undefined') return;
	$(document).ready(function(){
		$("body").on('click', 'a.c4d-woo-qv__link', function(event){
			event.preventDefault();
			var self = this;

			if (!$(self).hasClass('initEvent')) {
				$(self).addClass('initEvent');
				
				var productId = $(self).data('product_id'),
				uid = $(self).data('uid');
				$(self).addClass('loading');

				$.get({
					url: woocommerce_params.ajax_url,
					data: {
						action: 'c4d_woo_qv_get_product',
				  	pid: productId,
				  	uid: uid
					}
				}).done(function(res){
					$(self).removeClass('loading');
					$(self).parent().append(res);

					$(self).fancybox({
						'transitionIn'	:	'elastic',
						'transitionOut'	:	'elastic',
						'speedIn'		:	600, 
						'speedOut'		:	200, 
						'overlayShow'	:	false,
						'width'           : 'auto',
	        	'height'          : 'auto',
	        	'maxWidth'		: 1200,
	        	'maxHeight'		: 1000,
	        	'autoSize'		: true,
	        	'scrolling'		: 'yes',
						'iframe' : {
			        'css' : {
			            'width'  : '800px',
			            'height' : '600px'
			  			}
					  }
					});		

					var id = $(self).attr('href');

					if ($(id).find('.c4d-woo-qv-gallery .item-slide').length > 2) {
						setTimeout(function(){
							$(id).find('.c4d-woo-qv-gallery').slick({
								accessibility: true,
							  slidesToShow: 1,
							  slidesToScroll: 1,
							  adaptiveHeight: true,
							  lazyLoad: 'ondemand',
							  asNavFor: $(id).find('.c4d-woo-qv-gallery-nav')
							});	

							$(id).find('.c4d-woo-qv-gallery-nav').slick({
							  slidesToShow: 3,
							  slidesToScroll: 1,
							  asNavFor: $(id).find('.c4d-woo-qv-gallery'),
							  centerMode: true,
							  focusOnSelect: true
							});

							setTimeout(function(){
								var currentSlide = $(id).find('.c4d-woo-qv-gallery .slick-current');
								currentSlide.trigger('resize');
					    	currentSlide.trigger( 'zoom.destroy' );
								currentSlide.zoom();	
							}, 300);
							

							$(id).find('.c4d-woo-qv-gallery').on('afterChange', function(event, slick, currentSlide){
							  var currentSlide = $(id).find('.c4d-woo-qv-gallery').find('[data-slick-index="'+currentSlide+'"]');
						    	currentSlide.trigger( 'zoom.destroy' );
									currentSlide.zoom();
							});
						}, 100);
					}
						
					$(self).trigger('click');

					//gallery swatch
					if (typeof c4dWooVS != 'undefined') {
						c4dWooVS.singleColorBox();
					}
				});
			}
		});
		
		// quickview add to cart by ajax
		$('body').on('click', '.c4d-woo-qv form button[type="submit"]', function(event){
			event.preventDefault();
			var form = $(this).parents('form'),
			addButton = form.find('[name="add-to-cart"]'),
			pid = addButton.addClass('loading').val(),
			qty = $(this).find('[name="quantity"]').val();

			$.ajax({
         type: "POST",
         url: '/?wc-ajax=add_to_cart',
         data: {
      		'product_id' : pid,
      		'quantity': qty
         }
      }).done(function(){
      	$('.fancybox-close-small').trigger('click');
      });
			return false;
		});
	});
})(jQuery);