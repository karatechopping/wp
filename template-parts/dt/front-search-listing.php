<?php 
    global $business_nm,$add_nm,$listing_category;
    if(isset($_GET['listing_keyword']) || isset($_GET['add_listing_keyword1']) || isset($_GET['s_listing_category'])) {
    	$business_nm = $_GET['listing_keyword'];
        $add_nm = $_GET['add_listing_keyword1'];
        $listing_category = $_GET['s_listing_category'];
    }
?>
<div class="row justify-content-center">
	<!--added by me-->
	<!-- search by listing name -->
	<div class="col-lg-4">
		<?php if($business_nm != ''){ ?>
				<input class="form-control listing_keyword" type="text" name="listing_keyword" id="listing_keyword" placeholder="<?php echo __('Search by: Business Name', 'directorytheme'); ?>" value="<?php echo $business_nm; ?>">
		<?php }else{ ?>
				<input class="form-control listing_keyword" type="text" name="listing_keyword" id="listing_keyword" placeholder="<?php echo __('Search by: Business Name', 'directorytheme'); ?>">
		<?php 
			} 
			$schbn = new WP_Query( array( 
				'post_type' => 'listings', 
				'posts_per_page' => '-1',
				'post_status' => 'publish',
			)); 
		?>
		<div class="srldiv">
			<?php
			while ($schbn->have_posts()) : $schbn->the_post();
				$pltit   = get_the_title();
				$new_str = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', ' ', $pltit); ?>
				<input class="fbnh" type="hidden" data-businame="<?php echo $pltit; ?>">
			<?php endwhile; ?>
		</div>
	</div>

	<!-- search by address name -->
	<div class="col-lg-4">
		<?php if($add_nm != ''){ ?>
				<input class="form-control" type="text" name="add_listing_keyword1" id="listing_address" placeholder="<?php echo __('Search by: Address', 'directorytheme'); ?>" value = "<?php echo $add_nm;?>">
		<?php } else { ?>
				<input class="form-control" type="text" name="add_listing_keyword1" id="listing_address" placeholder="<?php echo __('Search by: Address', 'directorytheme'); ?>">
		<?php 
			} 
			$schbn = new WP_Query( array( 
				'post_type' => 'listings', 
				'posts_per_page' => '-1',
				'post_status' => 'publish',
			)); 
		?>
		<div class="sbaddress1">
			<?php
			if ( $schbn->have_posts() ) :
				while ( $schbn->have_posts() ) : $schbn->the_post();
					$sbad1  = get_field('address');
					$my_add = print_r($sbad1['address'],true); ?>
					<input class="s_listing_address" type="hidden" data-address="<?php echo $my_add; ?>">
				<?php
				endwhile; 
			endif; ?>
		</div>
	</div>

	<!-- search by category name -->
	<div class="col-lg-4">
		<div class="dropdown dropdown-category">
			<select name="dropdown_category4" class="btn btn-secondary dropdown-toggle" type="button" id="listing_category">
				<option value="" selected><?php _e('Search by: Category', 'directorytheme'); ?></option>
				<?php
				$terms_links = get_terms('listing-categories');
				foreach( $terms_links as $terms_link ) { ?>
					<option class="dropdown-item dropdown-cat" value="<?php echo "{$terms_link->slug}"; ?>"><?php echo "{$terms_link->name}"; ?></option>
				<?php
				} ?>
			</select>
			<?php 
				if($listing_category != ''){ 
			?>
					<input type="hidden" name="s_listing_category" id="s_listing_category" value ="<?php echo $listing_category; ?>">
			<?php } else { ?>
					<input type="hidden" name="s_listing_category" id="s_listing_category">
			<?php } ?>
		</div><!--dropdown-->
	</div><!--col-->
</div><!--row-->
<div class="row justify-content-end align-items-center" style="margin-top: 15px;">
	<div class="col-lg-auto">
		<button type="button" class="btn btn-default reset-btn" style="cursor: pointer;"><?php _e('Reset Filter', 'directorytheme'); ?></button>
	</div><!--col-->
	<div class="col-lg-auto">
		<button type="submit" id= "keyword-search" class="btn btn-secondary" style="cursor: pointer;"><?php _e('Search', 'directorytheme'); ?><span class="s-spinner"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></span></button>
	</div><!--col-->
</div><!--row-->