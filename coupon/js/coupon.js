jQuery(function( $ ) {
	var meta_boxes_coupon_actions = {
		init: function() {
			jQuery( 'select#acf-field_5aa8ea91702a1b1' ).on( 'change', function(){
				var select_val = jQuery( this ).val();

				if ( '0' === select_val ) {
					$( '#acf-field_5aa8ea91702a1b2' ).removeClass( 'input_price' ).addClass( 'input_decimal' );
				} else {
					$( '#acf-field_5aa8ea91702a1b2' ).removeClass( 'input_decimal' ).addClass( 'input_price' );
				}

				if ( select_val !== 'fixed_cart' ) {
					$( '.limit_usage_to_x_items_field' ).show();
				} else {
					$( '.limit_usage_to_x_items_field' ).hide();
				}
			}).trigger( 'change' );
			this.insert_generate_coupon_code_button();
			$( '.button.generate-coupon-code' ).on( 'click', this.generate_coupon_code );
		},
		insert_generate_coupon_code_button: function() {
			$( '.post-type-coupon' ).find( 'input#title' ).after(
				'<a href="#" class="button generate-coupon-code"> Generate coupon code </a>'
			);
        },
        generate_coupon_code: function( e ) {
			e.preventDefault();
			var coupon_code_field = $( '#title' ),
				coupon_code_label = $( '#title-prompt-text' ),
			    result = '',
			    characters = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
			for ( var i = 0; i < 8; i++ ) {
				result += characters.charAt(
					Math.floor( Math.random() * characters.length )
				);
			}
			coupon_code_field.trigger( 'focus' ).val( result );
			coupon_code_label.addClass( 'screen-reader-text' );
		}
	};
	meta_boxes_coupon_actions.init();
});
jQuery( document.body )
	.on( 'blur', '.input_decimal[type=text], .input_price[type=text]', function() {
		jQuery( '.wc_error_tip' ).fadeOut( '100', function() { jQuery( this ).remove(); } );
	})

	.on(
		'change',
		'.input_price[type=text], .input_decimal[type=text]',
		function() {
			var regex, decimalRegex,
				decimailPoint = '.';

			if ( jQuery( this ).is( '.input_price' ) ) {
				decimailPoint = '.';
			}

			regex        = new RegExp( '[^\-0-9\%\\' + decimailPoint + ']+', 'gi' );
			decimalRegex = new RegExp( '\\' + decimailPoint + '+', 'gi' );

			var value    = jQuery( this ).val();
			var newvalue = value.replace( regex, '' ).replace( decimalRegex, decimailPoint );

			if ( value !== newvalue ) {
				jQuery( this ).val( newvalue );
			}
		}
	)

	.on(
		'keyup',
		// eslint-disable-next-line max-len
		'.input_price[type=text], .input_decimal[type=text]',
		function() {
			var regex, error, decimalRegex;
			var checkDecimalNumbers = false;

			if ( jQuery( this ).is( '.input_price' ) ) {
				checkDecimalNumbers = true;
				regex = new RegExp( '[^\-0-9\%\\' + '.' + ']+', 'gi' );
				decimalRegex = new RegExp( '[^\\' + '.' + ']', 'gi' );
				error = 'i18n_mon_decimal_error';
			} else {
				checkDecimalNumbers = true;
				regex = new RegExp( '[^\-0-9\%\\' + '.' + ']+', 'gi' );
				decimalRegex = new RegExp( '[^\\' + '.' + ']', 'gi' );
				error = 'i18n_decimal_error';
			}

			var value    = jQuery( this ).val();
			var newvalue = value.replace( regex, '' );

			// Check if newvalue have more than one decimal point.
			if ( checkDecimalNumbers && 1 < newvalue.replace( decimalRegex, '' ).length ) {
				newvalue = newvalue.replace( decimalRegex, '' );
			}

			if ( value !== newvalue ) {
				jQuery( document.body ).triggerHandler( 'wc_add_error_tip', [ jQuery( this ), error ] );
			} else {
				jQuery( document.body ).triggerHandler( 'wc_remove_error_tip', [ jQuery( this ), error ] );
			}
		}
	);