<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div id="page-banner">
  <div class="inner">
    <h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentyseventeen' ); ?></h1>
  </div><!--inner-->
</div><!--page-banner-->

<div id="cp-container" class="cp-section page-not-found">
  <div class="inner">
    <div class="full-content">
			<div class="page-content">
				<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentyseventeen' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .page-content -->
    </div>
  </div><!--inner-->
</div><!--cp-container-->
<?php get_footer();