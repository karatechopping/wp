<?php

function my_comment_template( $comment, $args, $depth ) {
	?>
	<div class="comment">
		<?php if( get_field('rate', $comment) ): ?>

			<label>Rating</label>
<?php 
	$rating = get_field('rate', $comment); 
?>
<input type="hidden" class="rating_value" value="<?php echo $rating; ?>" />
<?php if($rating=="1") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="2") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="3") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="4") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="5") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
<?php endif; ?>
		<?php endif; ?>

	</div>


	<?php
}

//using callback to change just html utput on a comment
//html5 comment
function my_comments_callback($comment, $args, $depth){
   //checks if were using a div or ol|ul for our output
   $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
      <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
         <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
               <div class="comment-author vcard">
                  <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                  <?php printf( __( '%s' ), sprintf( '%s', get_comment_author_link( $comment ) ) ); ?> - <em><time datetime="<?php comment_time( 'c' ); ?>"><?php printf( __( '%1$s' ), get_comment_date( '', $comment ) ); ?></time></em>
               </div><!-- .comment-author -->

	<div class="comment_rating">
		<?php if( get_field('rate', $comment) ): ?>

<?php 
	$rating = get_field('rate', $comment); 
?>
<input type="hidden" class="rating_value" value="<?php echo $rating; ?>" />
<?php if($rating=="1") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="2") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="3") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="4") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i>
<?php endif; ?>
<?php if($rating=="5") : ?>
	<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
<?php endif; ?>
		<?php endif; ?>

	</div><!--comment_rating-->


               <?php if ( '0' == $comment->comment_approved ) : ?>
               <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
               <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content">
               <?php comment_text(); ?>
            </div><!-- .comment-content -->

         </article><!-- .comment-body -->
         <?php
}

?>