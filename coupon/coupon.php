<?php 
	function Coupon_custom_post_type() {
		// Set UI labels for Custom Post Type
	    $labels = array(
	        'name'                => _x( 'Coupon', 'Post Type General Name', 'directorytheme' ),
	        'singular_name'       => _x( 'Coupon', 'Post Type Singular Name', 'directorytheme' ),
	        'menu_name'           => __( 'Coupon', 'directorytheme' ),
	        'all_items'           => __( 'All Coupon', 'directorytheme' ),
	        'add_new_item'        => __( 'Add New Coupon', 'directorytheme' ),
	        'add_new'             => __( 'Add New', 'directorytheme' ),
	        'edit_item'           => __( 'Edit Coupon', 'directorytheme' ),
	        'update_item'         => __( 'Update Coupon', 'directorytheme' ),
	        'search_items'        => __( 'Search Coupon', 'directorytheme' ),
	        'not_found'           => __( 'Not Found', 'directorytheme' ),
	        'not_found_in_trash'  => __( 'Not found in Trash', 'directorytheme' ),
	    );
		// Set other options for Custom Post Type
	    $args = array(
	        'label'               => __( 'Coupon', 'directorytheme' ),
	        'description'         => __( 'Movie news and reviews', 'directorytheme' ),
	        'labels'              => $labels,  
	        'supports'            => array( 'title'),     
	        'hierarchical'        => false,
	        'public'              => false,
	        'show_ui'             => true,
	        'show_in_menu'        => true,
	        'show_in_nav_menus'   => true,
	        'show_in_admin_bar'   => true,
	        'menu_position'       => 5,
	        'can_export'          => true,
	        'has_archive'         => true,
	        'exclude_from_search' => false,
	        'publicly_queryable'  => true,
	        //'capability_type'     => 'post',
	        'show_in_rest' => true, 
	    );
	    // Registering your Custom Post Type
	    register_post_type( 'coupon', $args );
	}
	add_action( 'init', 'Coupon_custom_post_type', 0 );

	function admin_scripts() {
		global $wp_query, $post;
		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';
		if ( in_array( $screen_id, array( 'coupon', 'edit-coupon' ) ) ) {
			wp_enqueue_script( 'coupon', get_theme_file_uri( '/coupon/js/coupon.js?'.time() ), array( 'jquery' ), '1.0', true );
			$locale  = localeconv();
			$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
			$params = array(
				/* translators: %s: decimal */
				'i18n_decimal_error'                => sprintf( __( 'Please enter with one decimal point (%s) without thousand separators.', 'directorytheme' ), $decimal ),
				'i18n_mon_decimal_error'            => sprintf( __( 'Please enter with one monetary decimal point (%s) without thousand separators and currency symbols.', 'woocommerce' ), '' ),
				'decimal_point'                     => $decimal,
				'ajax_url'                          => admin_url( 'admin-ajax.php' ),
				'strings'                           => array(),
				'nonces'                            => array(),
				'urls'                              => array(),
			);
			wp_localize_script( 'directorytheme_admin', 'directorytheme_admin', $params );
		}
	}
	add_action( 'admin_enqueue_scripts', 'admin_scripts');
?>