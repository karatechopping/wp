<?php
/**
 * The main template file
 */
?>
<?php get_header(); ?>

<?php 
	define('has_post_thumbnail', "has_post_thumbnail");
?>

	<div id="page-banner" class="<?php if(get_field('blog_banner', 'option')) : ?>has-banner<?php endif; ?>" style="background: <?php if(get_field('blog_banner', 'option')) : ?>url('<?php the_field('blog_banner', 'option'); ?>') no-repeat center transparent ;<?php else: ?><?php endif; ?>;">
		<div class="inner">
			<h1><?php echo __(get_the_title(get_option('page_for_posts')), 'directorytheme'); ?></h1>
		</div><!--inner-->
	</div><!--page-banner-->

	<div id="cp-container" class="cp-section">
		<div class="inner">
			<div class="full-content">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
							$side_img = get_field('featured_image');
							$sizeimg = "img_1000x600"; // (thumbnail, medium, large, full or custom size)
							$side_image = wp_get_attachment_image_src( $side_img, $sizeimg );
							$content = get_the_excerpt();
						?>

						<div class="col-lg-4">
							<div class="blog-item">
							<?php if(has_post_thumbnail): ?>	
								<div class="blog-img default_img">
									<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('img_1000x600'); ?></a>
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
					<?php endwhile; ?>
				<?php wp_pagenavi(); ?>
				<?php
				// If no content, include the "No posts found" template.
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>	
			</div>
		</div><!--inner-->
	</div><!--cp-container-->

	<script>
		jQuery('.default_img img').addClass('img-fluid');
		var divs = jQuery(".col-lg-4");
		for(var i = 0; i < divs.length; i+=3) {
		divs.slice(i, i+3).wrapAll("<div class='row'></div>");
		}	
	</script>

<?php get_footer();
