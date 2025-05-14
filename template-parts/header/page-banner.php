<div id="page-banner" class="3333 <?php if(get_field('banner')) : ?>has-banner<?php endif; ?>" style="<?php if(get_field('banner')) : ?>background: url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?> transparent ;<?php endif; ?>;">
	<div class="inner">
		<?php if(is_category()): ?>
    		<h1><?php echo single_cat_title(); ?></h1>
    	<?php else: ?>
    		<h1><?php the_title(); ?></h1>
    	<?php endif; ?>
	</div><!--inner-->
</div><!--page-banner-->