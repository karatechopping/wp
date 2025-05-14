<?php
/**
 * The template for displaying all single posts

 */

get_header(); ?>

<?php
$side_img 	= get_field('featured_image');
$sizeimg 	= "full"; // (thumbnail, medium, large, full or custom size)
$side_image = wp_get_attachment_image_src( $side_img, $sizeimg );
$content 	= get_the_excerpt();
?>

<div id="page-banner" class="<?php if(get_field('featured_image')) : ?>has-banner blog-banner<?php endif; ?>" style="background: <?php if(get_field('featured_image')) : ?>url('<?php echo $side_image[0]; ?>') no-repeat center center transparent;<?php else: ?>#212121<?php endif; ?>;">
  <div class="inner">
    <h1><?php the_title(); ?></h1>
  </div>
</div>

<!-- This code is used for single ads display on page.
	Screenshort : https://prnt.sc/1umub44 -->
<div id="cp-container" class="cp-section">
  	<div class="inner">
		<div class="blog-content">
			<div class="row">

				<div class="col-lg-8">
					<?php
					while ( have_posts() ) : the_post();
						$image = get_field('ads_image');
						$size = 'img_1000x600'; // (thumbnail, medium, large, full or custom size) ?>
						<div class="ads-listing">
							<div class="ads-img">
								<a href="<?php the_field('ads_url'); ?>" target="_blank"><?php echo wp_get_attachment_image( $image, $size ); ?></a>
								<h3><?php the_title(); ?></h3>
							</div><!--fl-img-->
							<div class="ads_content">
								<?php the_field('ads_description'); ?>
								<a href="<?php the_field('ads_url'); ?>" target="_blank" class="global-btn btn-full"><?php _e('Read More', 'directorytheme'); ?></a>
							</div>
						</div>
						<?php
					endwhile; ?>
				</div>

				<div class="col-md-4">
					<div class="sidebar sidebar-blog">
						<?php dynamic_sidebar( 'sidebar-blog' ); ?>

						<aside>
							<div class="widget side-post">
								<h5 class="widget-title"><?php echo __('Recent Posts', 'directorytheme'); ?></h5>

								<?php $cpost = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => '4' ) );

								if ( $cpost->have_posts() ) :

									while ( $cpost->have_posts() ) : $cpost->the_post();

									$side_img = get_field('featured_image');
									$sizeimg = "img_400x400"; // (thumbnail, medium, large, full or custom size)
									$side_image = wp_get_attachment_image_src( $side_img, $sizeimg ); ?>

									<div class="side-post">
										<?php
										if($side_image!="") : ?>
											<a href="<?php the_permalink(); ?>"><img src="<?php echo $side_image[0]; ?>" alt="<?php echo get_the_title(get_field('featured_image')) ?>" class="img-fluid" /></a>
										<?php
										endif; ?>
										<div class="side-post-entry" <?php if($side_image=="") : ?>style="margin-left: 0;"<?php endif; ?>>
											<h4 <?php if($side_image=="") : ?>style="margin-bottom: 4px;"<?php endif; ?>><a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a></h4>
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