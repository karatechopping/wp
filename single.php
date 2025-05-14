<?php
/**
 * The template for displaying all single posts

 */

get_header(); ?>

<?php
$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
$img_url = !empty($large_image_url) ? $large_image_url[0] : ''; ?>

<div id="page-banner" class="
	<?php
	if( !empty($img_url) ) { ?> has-banner blog-banner <?php } ?>"
	style="background:
		<?php
		if(!empty($img_url)) :?> url('<?php echo $img_url; ?>') no-repeat center center transparent;
		<?php else: ?> #212121
		<?php
		endif; ?>;">

	<div class="inner">
		<h1><?php the_title(); ?></h1>
	</div>
</div>

<div id="cp-container" class="cp-section">
  	<div class="inner">
		<div class="blog-content">
			<div class="row">
				<div class="col-lg-8">
					<?php while ( have_posts() ) : the_post(); ?>

						<h2 class="blog-date"><?php echo get_the_date('j'); ?>
                            <span><?php echo get_the_date('F Y'); ?></span>
                        </h2>
						<div class="cp-entry">
							<?php the_content(); ?>
						</div>
					<?php endwhile; ?>
				</div><!--col-->

				<div class="col-md-4">
					<div class="sidebar sidebar-blog">
						<?php dynamic_sidebar( 'sidebar-blog' ); ?>
						<aside>
							<div class="widget side-post">
								<h5 class="widget-title"><?php echo __('Recent Posts', 'directorytheme'); ?></h5>
								<?php
								$cpost = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => '4' ) );
								if ( $cpost->have_posts() ) :
									while ( $cpost->have_posts() ) : $cpost->the_post();
										$side_img 		 = get_field('featured_image');
										$sizeimg 		 = "img_400x400"; // (thumbnail, medium, large, full or custom size)
										$side_image 	 = wp_get_attachment_image_src( $side_img, $sizeimg );
										$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
										$img_url1 		 = print_r($large_image_url[0],true); ?>

										<div class="side-post">
											<?php
											if($img_url1 !="") : ?>
												<a href="<?php the_permalink(); ?>">
												<img src="<?php echo $img_url1 ?>" alt="<?php echo get_the_title() ?>" class="img-fluid"/></a>
											<?php
											endif; ?>
											<div class="side-post-entry" <?php if($img_url1=="") : ?>style="margin-left: 0;"<?php endif; ?>>
												<h4 <?php if($img_url1=="") : ?>style="margin-bottom: 4px;"<?php endif; ?>>
												<a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a></h4>
												<div class="date-time item"><i class="fa fa-calendar-o"></i> <?php echo get_the_date(); ?></div>
											</div>
										</div>
									<?php
									endwhile;
									wp_reset_postdata(); ?>
								<?php
								endif; ?>
							</div>
						</aside>
					</div><!--sidebar-->
				</div><!--col-md-4-->

			</div><!--row-->

		</div><!--blog-content-->
  	</div><!--inner-->
</div><!--cp-container-->

<script>
	jQuery('#cp-container img').addClass('img-fluid');
</script>

<?php get_footer();