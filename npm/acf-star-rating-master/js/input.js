(function($){
	
	
	function initialize_field( $el ) {

		var container = $el;
		var star_list = $("ul", container);
		var star_list_items = $("li", star_list);
		var star_list_item_stars = $("i", star_list_items);
		var star_field = $("input#star-rating", container);
		var clear_value_button = $("a.clear-button", container);

		star_list_items
			.bind("click", function(e){
				e.preventDefault();
				
				var star_value = $(this).index();

				star_field.val(star_value + 1);
				clearActiveStarClassesFromList();

				star_list_items.each(function(list_index){
					if( list_index <= star_value ){
						addActiveStarClass( $("i", this) );
					}
				});
				
			});

		clear_value_button
			.bind("click", function(e){
				e.preventDefault();
				clearActiveStarClassesFromList();
				star_field.val(0);
			});

		function clearActiveStarClassesFromList(){
			star_list_item_stars.removeClass('fa-star').addClass('fa-star-o');
		}

		function addActiveStarClass( el ){
			el.removeClass('fa-star-o').addClass('fa-star');
		}
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'star_rating'
			acf.get_fields({ type : 'star_rating'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).live('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="star_rating"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);
jQuery(document).ready(function(){
    var ppinm=jQuery('input#acf-field_5a6004a17eceb').val();
    //alert(ppinm);
    if(ppinm == 'Side Bar Ad'){
        jQuery("input#acf-field_5a6004a17eceb").prop('disabled', true);
    }
    var ppplnm=jQuery('input#acf-field_5a6004a17eceb').val();
    if(ppplnm == 'Premium Listing'){
        jQuery("input#acf-field_5a6004a17eceb").prop('disabled', true);
    }
});
jQuery(document).ready(function(){
    jQuery('a.acf-button.button.button-primary.acf-gallery-add').text("Upload Images To Gallery");
});