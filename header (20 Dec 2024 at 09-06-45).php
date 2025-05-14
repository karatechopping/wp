<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php
			if(is_tax()){
				$taxonomy = get_queried_object();
				$category_banner_image = get_field('category_banner',get_queried_object());
				?>
					<meta property="og:title" content="<?php echo $taxonomy->name; ?>">
					<meta property="og:image" content="<?php echo $category_banner_image; ?>">
					<meta property="og:url" content="<?php echo get_the_permalink($taxonomy->id); ?>">
				<?php
			}else if(get_post_type() == 'listings' || get_post_type() == 'post'){
				?>
					<meta property="og:title" content="<?php echo get_the_title(); ?>">
					<meta property="og:image" content="<?php echo get_the_post_thumbnail_url(); ?>">
					<meta property="og:url" content="<?php echo get_the_permalink(); ?>">
				<?php
			}else{
				?>
					<meta property="og:title" content="<?php echo get_the_title(); ?>">
					<meta property="og:image" content="<?php the_field('logo', 'option') ?>">
					<meta property="og:url" content="<?php echo get_the_permalink(); ?>">
				<?php
			}
		?>
		<!-- Bootstrap -->
		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap-grid.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/default.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
		<link href="<?php echo get_template_directory_uri(); ?>/css/animate.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/lightbox.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,400i,500,600,600i,700,800,900|lato: 300,400,400i,700" rel="stylesheet">
		<!-- Owl Carousel -->
		<link href="<?php echo get_template_directory_uri(); ?>/css/owl.carousel.min.css?<?php echo time(); ?>" rel="stylesheet">
		<link href="<?php echo get_template_directory_uri(); ?>/css/owl.theme.default.min.css?<?php echo time(); ?>" rel="stylesheet">

		<?php if(get_field('activate_custom_color','option')):?>
			<link href="<?php echo get_template_directory_uri(); ?>/style-color.css?<?php echo time(); ?>" rel="stylesheet">
		<?php else : ?>	
			<link href="<?php echo get_template_directory_uri(); ?>/color.css?<?php echo time(); ?>" rel="stylesheet">
		<?php endif; ?>	

		<link rel="icon" href="<?php the_field('favicon', 'option') ?>" sizes="32x32">	

		<?php wp_head(); 
		/* Check API key availble or not in backend. */
		$fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
		if(!empty($fetch_plugin_api_key)) $plugin_api_key =  $fetch_plugin_api_key["api_key"];

		$cmnapikey = get_field('googlemapcommonapikey','option');

		if (isset($fetch_plugin_api_key["api_key_front_end"]) && !empty($fetch_plugin_api_key["api_key_front_end"])) $plugin_api_key = $fetch_plugin_api_key["api_key_front_end"];
		$cmnapikey_front_end = get_field('googlemapcommonapikey_frontend','option');
		if (!empty($cmnapikey_front_end)) $cmnapikey = $cmnapikey_front_end;
		if(is_page_template('contact.php') || is_page_template('add-listing.php') ||  is_page_template( 'edit_listing.php')): ?>

		<script src="https://maps.googleapis.com/maps/api/js?key=<?php if(!empty($plugin_api_key)){ echo $plugin_api_key; } else { echo $cmnapikey; } ?>&libraries=places"></script>
		<?php endif; ?>

		<!-- Custom Color Activated -->
		<?php
			$bannerimg	  = get_field('banner','option');
			$gtopn		  = get_field('activate_custom_color','option');
			$prmclr		  = get_field('primary_color','option');
			$ppmclr		  = get_field('ppb_bg_color','option');
			$tpmbgclr	  = get_field('menu_bg_color','option');
			$tpmnhvrclr	  = get_field('top_menu_hover_color','option');
			$mmbgclr	  = get_field('main_menu_bg_color','option');
			$footer 	  = get_field('footer_bg_color','option');
			$body_txt_clr = get_field('body_text_color','option');
			$title_clr 	  = get_field('title_color','option');

			if($gtopn == 1){
				echo '<style>';				
				echo '#cp-header .navbar-nav li:hover ul, #cp-header .navbar-nav.submit-listing li a, a.add-listing, .bsr_content a { background: '.$prmclr.'; }';
				echo 'body #page-banner,.pricing .pricing-widget:hover .pricing-content, .pricing .pricing-widget.active .pricing-content, .pricing .pricing-widget:hover .pricing-header .price-cost, .pricing .pricing-widget.active .pricing-header .price-cost, .page-template-pricing #page-banner { background: '.$ppmclr.'; }';
				echo '#cp-header .navbar-nav li.current-menu-item a { color: '.$prmclr.'; }';
				echo '.pricing-widget  h3.pricing-title { color: '.$ppmclr.' !important; }';
				echo '.pricing-widget.main.active h3.pricing-title, .pricing-widget:hover h3.pricing-title { color: white !important; }';
				echo 'div#cp-header { background: '.$mmbgclr.' !important; }';
				echo '#cp-top { background: '.$tpmbgclr.' !important; }';
				echo '.c-top-right.pull-right ul li a:hover, .c-top-right a.account-link:hover, #cp-top .social ul li a:hover { color: '.$tpmnhvrclr.' !important; }';
				echo '#cp-header .navbar-nav li ul li.menu-item-has-children ul li.menu-item-has-children ul li { background: '.$prmclr.' !important; }';
				echo '#footer{background :'.$footer.';}';
				echo 'body .listing_search button.btn[type="submit"]{ background: '.$prmclr.' !important; }';
				require get_template_directory() . '/npm/style-color.php';
				echo '</style>';			
			}
		?>
	</head>

	<div class="header-txt" style=" display : none;">
		<?php $head_val = the_field('headertext','options');  echo $head_val; ?>
	</div>

	<!-- main body section start --->
	<body <?php body_class(); ?>>
		<div id="cp-wrapper">
			<div id="cp-top">
				<div class="inner">
					<div class="row no-gutters">
						<div class="col-6">
							<div class="social">
								<ul>
									<?php
									$mail_icon = get_field('email', 'option');
									$tel_icon  = get_field('phone', 'option');
									if($mail_icon): ?>
										<li><a href="mailto:<?php the_field('email', 'option') ?>"><i class="fa fa-envelope-o"></i> <span><?php the_field('email', 'option') ?></span></a></li>
									<?php endif; ?>

									<?php if($tel_icon): ?>
											<li><a href="tel:<?php the_field('phone', 'option') ?>"><i class="fa fa-phone"></i> <span><?php the_field('phone', 'option') ?></span></a></li>	
									<?php endif; ?>
								</ul>
							</div><!--social-->
						</div><!--col-->

						<div class="col-6 clearfix">
							<div class="c-top-right pull-right">
								<div class="c-top-left">
									<ul>
										<?php if ( has_nav_menu( 'top_nav' ) ) : ?>
											<?php wp_nav_menu( array(
												'theme_location' => 'top_nav',
												'container'		 => false,
												'items_wrap' 	 => '%3$s',
											) ); ?>
										<?php else : ?>
											<li><a href="<?php echo home_url(); ?>"><?php echo __('Home', 'directorytheme'); ?></a></li>
										<?php endif; ?>	
									</ul>
								</div><!--c-top-left-->
								<?php
									$login_register_on_off   = get_option('options_login_register_on_off');
									if($login_register_on_off != '1'):
										$home_url = get_the_permalink(); ?>
										<div class="dropdown">
											<a href="<?php the_field('add_listing_page', 'option'); ?>" class="add-listing" data-toggle="dropdown"><i class="fa fa-plus"></i></a>
											<div class="dropdown-menu submit_btn" aria-labelledby="dropdownMenuButton">
												<a class="dropdown-item" href="<?php the_field('add_listing_page', 'option'); ?>"><?php echo __('Submit a Listing', 'directorytheme'); ?></a>
												<?php
												global $current_user; 
												$user_count = count_user_posts($current_user->ID, 'listings');
												if ( is_user_logged_in() && $user_count !="0" ) : ?>
													<a id="papltm" class="dropdown-item" href="<?php the_field('pay_listing_page', 'option'); ?>"><?php echo __('Purchase a Premium Listing', 'directorytheme'); ?></a> 
												<?php endif; ?>
												<!-- Check user login or not -->
												<?php  if ( is_user_logged_in() ) : ?>  
													<a class="dropdown-item" href="<?php echo wp_logout_url($home_url); ?>"><?php echo __('Logout', 'directorytheme'); ?></a>  
												<?php endif; ?>
											</div>
										</div>
										<?php if ( !is_user_logged_in() ) : ?>
											<a href="<?php the_field('account_page ', 'option'); ?>" class="account-link"><?php echo __('Login / Register', 'directorytheme'); ?></a>
										<?php else : ?>
											<a href="<?php the_field('account_page ', 'option'); ?>" class="account-link"><?php echo __('Account', 'directorytheme'); ?></a>	
										<?php endif; 
									endif;
								?>
							</div><!--c-top-right-->
						</div>
					</div>
				</div>
			</div>
			
			<!--- Top menu of the theme -->
		  	<?php 
			$sticky_op_val   = get_option('options_header_sticky');
			$sticky_op_class = '';
			$home_url 		 = home_url();
			$logo_url 		 = get_field('logo', 'option');
			if($sticky_op_val == '1') $sticky_op_class = 'sticky_header';
			$add_listing_url = get_field('add_listing_page', 'option');
			$sub_btn = get_field('submit_listing_btn', 'option') ?: __('Submit a Listing', 'directorytheme'); ?>
			
			<header>
				<div id="cp-header" class="<?php echo $sticky_op_class; ?>">
					<div class="inner">
						<nav class="navbar navbar-expand-lg navbar-light">
							<a class="navbar-brand" href="<?= $home_url; ?>"><img src="<?= $logo_url ?>" /></a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainanv"  aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse justify-content-end" id="mainanv">	
								<?php 
								if ( has_nav_menu( 'top' ) ) :
									wp_nav_menu( array(
										'theme_location' => 'top',
										'menu_id'        => 'top-menu',
										'container'		 => '',
										'items_wrap' 	 => '<ul id="%1$s" class="%2$s navbar-nav">%3$s</ul>',
									) ); else : ?>
									<ul class="navbar-nav">
										<li><a href="<?php echo home_url(); ?>"><?php echo __('Home', 'directorytheme'); ?></a></li>
									</ul>
								<?php 
								endif; ?>
								
								<ul class="navbar-nav top_nav_mov">
									<?php 
									if (has_nav_menu('top_nav')) {
										wp_nav_menu([
											'theme_location' => 'top_nav',
											'container'      => false,
											'items_wrap'     => '%3$s',
										]);
									} else {
										echo "<li><a href='{$home_url}'>" . __('Home', 'directorytheme') . "</a></li>";
									}
									?>
								</ul>				  
								<ul class="submit-listing navbar-nav">
									<li><a href="<?= $add_listing_url; ?>"><?= $sub_btn; ?></a></li>
								</ul>		  
							</div>
						</nav>     
					</div>
				</div>
			</header>