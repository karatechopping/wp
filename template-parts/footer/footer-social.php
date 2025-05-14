<?php
/**
 * Displays footer site info
 *
 * @package WordPress
 * @subpackage Directory_Theme
 * @since 1.0
 * @version 1.0
 */

?>
<ul class="footer-social">
    <?php if(get_field('facebook', 'option')) : ?>
        <li><a href="<?php the_field('facebook', 'option') ?>" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('google', 'option')) : ?>
        <li><a href="<?php the_field('google', 'option') ?>" target="_blank"><i class="fa-brands fa-google"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('linkedin', 'option')) : ?>
        <li><a href="<?php the_field('linkedin', 'option') ?>" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('yelp', 'option')) : ?>
        <li><a href="<?php the_field('yelp', 'option') ?>" target="_blank"><i class="fa-brands fa-yelp"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('twitter', 'option')) : ?>
        <li><a href="<?php the_field('twitter', 'option') ?>" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('instagram', 'option')) : ?>
        <li><a href="<?php the_field('instagram', 'option') ?>" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('youtube', 'option')) : ?>
        <li><a href="<?php the_field('youtube', 'option') ?>" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('vimeo', 'option')) : ?>
        <li><a href="<?php the_field('vimeo', 'option') ?>" target="_blank"><i class="fa-brands fa-vimeo-v"></i></a></li>
    <?php endif; ?> 
    <?php if(get_field('tiktok', 'option')) : ?>
        <li><a href="<?php the_field('tiktok', 'option') ?>" target="_blank"><i class="fa-brands fa-tiktok"></i></a></li>
    <?php endif; ?>
    <?php if(get_field('whatsapp', 'option')) : ?>
        <li><a href="<?php the_field('whatsapp', 'option') ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i></a></li>
    <?php endif; ?>
    <?php if(get_field('gmb_link', 'option')) : ?>
        <li>
            <a href="<?php the_field('gmb_link', 'option') ?>" target="_blank">
                <span class="gmb_social">
                    <svg height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m22 8.5c0 1.37-1.12 2.5-2.5 2.5s-2.5-1.13-2.5-2.5c0 1.37-1.12 2.5-2.5 2.5s-2.5-1.13-2.5-2.5c0 1.37-1.12 2.5-2.5 2.5s-2.5-1.13-2.5-2.5c0 1.37-1.12 2.5-2.5 2.5s-2.5-1.13-2.5-2.5l1.39-5.42s.29-1.08 1.31-1.08h14.6c1.02 0 1.31 1.08 1.31 1.08zm-1 3.7v7.8c0 1.1-.9 2-2 2h-14c-1.1 0-2-.9-2-2v-7.8c.46.19.97.3 1.5.3.95 0 1.82-.33 2.5-.88.69.55 1.56.88 2.5.88.95 0 1.82-.33 2.5-.88.69.55 1.56.88 2.5.88.95 0 1.82-.33 2.5-.88.68.55 1.56.88 2.5.88.53 0 1.04-.11 1.5-.3m-2 5.13c0-.2 0-.41-.05-.63l-.03-.16h-2.97v1.17h1.81c-.06.22-.14.44-.31.62-.33.33-.78.51-1.26.51-.5 0-.99-.21-1.35-.56-.69-.71-.69-1.86.02-2.58.69-.7 1.83-.7 2.55-.03l.14.13.84-.85-.16-.14c-.56-.52-1.3-.81-2.08-.81h-.01c-.81 0-1.57.31-2.14.87-.59.58-.92 1.34-.92 2.13 0 .8.31 1.54.88 2.09.58.57 1.39.91 2.22.91h.02c.8 0 1.51-.29 2.03-.8.47-.48.77-1.2.77-1.87z"/></svg>
                </span>
            </a>
        </li>
    <?php endif; ?>
</ul>
