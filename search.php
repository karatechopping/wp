<?php
/**
 * The template for displaying search results pages
 */

get_header(); ?>

<div id="page-banner" class="<?php if(get_field('blog_banner', 'option')) : ?>has-banner<?php endif; ?>" style="background: <?php if(get_field('blog_banner', 'option')) : ?>url('<?php the_field('blog_banner', 'option'); ?>') no-repeat center transparent;<?php else: ?>#212121<?php endif; ?>;">
	<div class="inner">
		<?php if ( have_posts() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentyseventeen' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php else : ?>
			<h1 class="page-title"><?php _e( 'Nothing Found', 'twentyseventeen' ); ?></h1>
		<?php endif; ?>
	</div>
</div>

<div id="cp-container" class="cp-section">
	<div class="inner">
    	<div class="full-content">
		<?php 
		if ( have_posts() ) :
			$dorfi		= get_field('s_default_featured_image','option');
			$sizeimg 	= "img_1000x600"; // (thumbnail, medium, large, full or custom size)
			$defaultImg = get_template_directory().'/images/Listing-Placeholder.png';
			
			// Start the loop.
			while ( have_posts() ) : the_post();				
				$side_image = wp_get_attachment_image_src( $post_img, $sizeimg );
				$content 	= string_limit_words(get_the_excerpt(), 15);
				$value 		= get_field('field_5a41e122e2b8e');
				$a			= wp_get_attachment_image_src( $value, $size = 'thumbnail');
				$post_img	= get_field('featured_image');
				if (is_numeric( $post_img )) {        
					$post_img = wp_get_attachment_url($post_img);
				} else {
					$post_img = get_field('featured_image');
				} ?>

				<div class="col-lg-4">
					<div class="blog-item">
					<?php if(get_field('featured_image')){ ?>	
						<div class="blog-img blogimg_size">
							<a href="<?php the_permalink(); ?>"><img src="<?php if(is_numeric($post_img)){ echo $a[0]; } else { echo $post_img; } ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid" /></a>
							<h2 class="our-blog-date"><?php echo get_the_date('j'); ?><span><?php echo get_the_date('F'); ?> <?php echo get_the_date('Y'); ?></span></h2>
						</div>
					<?php } else if(!empty($dorfi)) { ?>	
						<div class="blog-img blogimg_size">
							<a href="<?php the_permalink(); ?>"><img src="<?php echo $dorfi; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid" /></a>
							<h2 class="our-blog-date"><?php echo get_the_date('j'); ?><span><?php echo get_the_date('F'); ?> <?php echo get_the_date('Y'); ?></span></h2>
						</div>
					<?php } else { ?>	
						<div class="blog-img blogimg_size">
							<a href="<?php the_permalink(); ?>"><img src="<?= $defaultImg; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid" /></a>
							<h2 class="our-blog-date"><?php echo get_the_date('j'); ?><span><?php echo get_the_date('F'); ?> <?php echo get_the_date('Y'); ?></span></h2>
						</div>
					<?php } ?>
						<div class="blog-entry">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?= $content; ?>...</p>
						</div>
					</div>
				</div>
			<?php 
			endwhile; 
			wp_pagenavi();
			// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; ?>	
    	</div>
	</div><!--inner-->
</div><!--cp-container-->

<script>
	var divs = jQuery(".col-lg-4");
	for(var i = 0; i < divs.length; i+=3) {
	  divs.slice(i, i+3).wrapAll("<div class='row'></div>");
	}	
</script>

<?php get_footer();