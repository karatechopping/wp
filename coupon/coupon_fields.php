<?php 
	acf_add_local_field_group(array(
		'key' => 'group_5a41cc3651702',
		'title' => 'Coupon',
		'fields' => array(
			array(
				'key' => 'field_5aa8ea91702a1',
				'label' => __( 'General', 'directorytheme' ),
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'left',
				'endpoint' => 0,
			),
			array(
				'key' 	=> 'field_5aa8ea91702a1b1',
				'label' => __( 'Discount type', 'directorytheme'),
				'name' 	=> 'discount_type',
				'type' 	=> 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' 	=> '',
				),
				'choices' => array(
					0 => 'Percentage discount',
					1 => 'Fixed cart discount',
				),
				'default_value' => array(
					0 => 0,
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'return_format' => 'value',
				'placeholder' => '',
			),
			array(
				'key' 	=> 'field_5aa8ea91702a1b2',
				'label' => __( 'Discount amount/percentage', 'directorytheme'),
				'name' 	=> 'discount_amount',
				'type' 	=> 'text',
				'instructions' => '',
				'required' 	   => 0,
				'conditional_logic' => 0,
				'wrapper' 	=> array(
					'width' => '',
					'class' => '',
					'id' 	=> '',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_5aa8ea91702a1b3',
				'label' => __( 'Coupon expiry date' , 'directorytheme' ),
				'name' => 'exp_date',
				'type' => 'date_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
			),
			array(
				'key' => 'field_5aa8ea91702a2',
				'label' => __( 'Usage limit', 'directorytheme' ),
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'left',
				'endpoint' => 0,
			),
			array(
				'key' 	=> 'field_5aa8ea91702a2b1',
				'label' => __( 'Usage limit per coupon', 'directorytheme'),
				'name' 	=> 'usage_limit_per_coupon',
				'type' 	=> 'number',
				'instructions' => '',
				'required' 	   => 0,
				'conditional_logic' => 0,
				'wrapper' 	=> array(
					'width' => '',
					'class' => '',
					'id' 	=> '',
				),
				'default_value' => '',
			),
			array(
				'key' 	=> 'field_5aa8ea91702a2b2',
				'label' => __( 'Usage limit per user', 'directorytheme'),
				'name' 	=> 'usage_limit_per_user',
				'type' 	=> 'number',
				'instructions' => '',
				'required' 	   => 0,
				'conditional_logic' => 0,
				'wrapper' 	=> array(
					'width' => '',
					'class' => '',
					'id' 	=> '',
				),
				'default_value' => '',
			),
		),
		'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'coupon',
					),
			),
		),
		'menu_order' => 0,
		'style' 	 => 'default',
		'label_placement' 		=> 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' 		=> array(
			0 => 'featured_image',
		),
		'active' => 1,
		'description' => '',
	));
?>