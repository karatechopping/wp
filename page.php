<?php
/**
 * The template for displaying all pages
 *
 */

get_header();

$the_id = get_the_ID();
$check_page_bann_switcher_meta  = metadata_exists( 'post', $the_id, 'show_hide_page_bann_switcher' );
$bnner_show_hide_flag           = get_post_meta( $the_id,'show_hide_page_bann_switcher',true );
$check_page_title_switcher_meta = metadata_exists( 'post', $the_id, 'show_hide_page_title_switcher' );
$page_title_show_hide_flag      = get_post_meta( $the_id,'show_hide_page_title_switcher',true );
  
if($bnner_show_hide_flag == 1 || $check_page_bann_switcher_meta === false): ?>
  <div id="page-banner" class="<?php if(get_field('banner')) : ?>has-banner<?php endif; ?>" style="background: <?php if(get_field('banner')) : ?>url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?> transparent;<?php endif; ?>;">
    <div class="inner">
        <?php if($page_title_show_hide_flag == 1 || $check_page_title_switcher_meta === false): ?>
        <h1>
            <?php
              $queried_object = get_queried_object();        
              echo $queried_object->post_title; 
            ?>
          </h1>
        <?php endif; ?>
    </div><!--inner-->
  </div><!--page-banner-->
<?php 
endif; ?>

<div id="cp-container" class="cp-section">
  <div class="inner">
    <div class="full-content">
      <?php 
      while ( have_posts() ) : the_post(); ?>
        <div class="cp-entry clearfix">
          <?php the_content(); ?>
        </div><!--cp-entry-->
      <?php 
      endwhile; ?>
      </div>
  </div><!--inner-->
</div><!--cp-container-->

<script>
	jQuery('#cp-container img').addClass('img-fluid');
</script>

<?php get_footer();