jQuery(window).bind('load', function() { // makes sure the whole site is loaded
	jQuery('#status').fadeOut(); // will first fade out the loading animation
		jQuery('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
	jQuery('body').delay(350).css({'overflow-y':'visible'});
});
jQuery(document).ready(function(){
	jQuery('.frg_pwd').click(function(){
    	jQuery('#cp-container').addClass('fpfrmbg');
		jQuery('.frgpwd_form').show();
	});
	if(jQuery('body').hasClass('home')){
		var sldrspd=jQuery('#sliderspeedid').attr('data-sliderspeed');
		var sdspd=sldrspd*1000;
		//alert(sdspd);
		jQuery('.owl-carousel').owlCarousel({
		    loop:true,
		    margin:10,
		    nav:true,
		   	autoplay: true,
			autoplayTimeout: sdspd,
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:1
		        },
		        1000:{
		            items:1
		        }
		    }
		});
	}
	if(jQuery('body').hasClass('single-listings')){
		/*jQuery('.review-owlcar').slick({
	  		slidesToShow: 1,
	  		slidesToScroll: 1,
	  		autoplay: true,
	  		autoplaySpeed: 5000,
	  		arrows: true,
	 	});*/
	 	jQuery('[data-toggle="tooltip"]').tooltip({
	    	content  	: null,
	    	persistent 	: false,
	    	plainText 	: false,
	  	});
	}
	

	jQuery('.moreless-button').click(function() {
	  	jQuery('.caregory_wrap').slideToggle();
	  	if (jQuery('.moreless-button span').text() == "More Categories") {
	    	jQuery('.moreless-button span').text("Less Categories")
	  	} else {
	    	jQuery('.moreless-button span').text("More Categories")
	  	}
	});   
});

// safari browser class add 
var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    if( /^((?!chrome|android).)*safari/i.test(navigator.userAgent)){
     jQuery('body').addClass('safari');
}