<?php
/**
 * The template for displaying archive pages
 */

get_header(); 

get_template_part('/template-parts/header/page-banner'); ?>

<div id="cp-container" class="cp-section">
	<div class="inner">
    	<div class="full-content">
			<?php 
			if ( have_posts() ) :
				// Start the loop.
				while ( have_posts() ) : the_post();

					$side_img 	= get_field('featured_image');
					$sizeimg  	= "img_1000x600"; // (thumbnail, medium, large, full or custom size)
					$side_image = wp_get_attachment_image_src( $side_img, $sizeimg );
					$content 	= get_the_excerpt(); 
					?>
					<div class="col-lg-4">
						<div class="blog-item">
							<?php if(has_post_thumbnail()): ?>	
								<div class="blog-img default_img">
									<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('img_1000x600', array( 'class' => 'img-fluid' )); ?></a>
									<h2 class="our-blog-date"><?php echo get_the_date('j'); ?><span><?php echo get_the_date('F'); ?> <?php echo get_the_date('Y'); ?></span></h2>
								</div>	
							<?php elseif(get_field('featured_image')): ?>
								<div class="blog-img">
									<a href="<?php the_permalink(); ?>"><img src="<?php echo $side_image[0]; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid" /></a>
									<h2 class="our-blog-date"><?php echo get_the_date('j'); ?><span><?php echo get_the_date('F'); ?> <?php echo get_the_date('Y'); ?></span></h2>
								</div>
							<?php endif; ?>	
							<div class="blog-entry">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo string_limit_words($content,15); ?>...</p>
							</div>
						</div><!--blog-item-->
					</div><!--col-->
					<?php 
				endwhile;
				wp_pagenavi();
				// If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif; 
			?>
    	</div>
  	</div><!--inner-->
</div><!--cp-container-->

<script>
	jQuery(function($) {
		var divs = $(".col-lg-4");
		for (var i = 0; i < divs.length; i += 3) {
			divs.slice(i, i + 3).wrapAll("<div class='row'></div>");
		}
	});
</script>

<?php get_footer();