<?php acf_form_head(); ?>
<?php
/**
 * template name: Pricing
 */
get_header();
$price_sign = get_post_meta(get_the_ID(), 'price_sign', true);
if (!empty($price_sign)):
    echo '<style>
.pricing .pricing-widget .pricing-header .price-cost .inner-price .inner-number:before{content:"' . $price_sign . '"}
</style>';
endif;
?>
<div id="page-banner" class="<?php if (get_field('banner')): ?>has-banner<?php
endif; ?>" style="background: <?php if (get_field('banner')): ?>url('<?php the_field('banner'); ?>') no-repeat <?php the_field('bg_position'); ?> transparent;<?php
endif; ?>;">
    <div class="inner">
         <h1><?php the_title(); ?></h1>
    </div><!--inner-->
</div><!--page-banner-->

<!-- Display pricing template on page with selected field by user in backend.
	Screenshort :  https://prnt.sc/1ubl3uw -->
<div id="cp-container" class="cp-section pricing">
    <div class="inner">
        <?php
        if (have_posts()):
            while (have_posts()):
                the_post();
                if (get_the_content()):
                    echo '<div class="taxonomy-description">';
                    the_content();
                    echo '</div>';
                endif;
            endwhile;
        endif; ?>

        <div class="row">
            <?php if (get_field('pricing')): ?>
            <?php while (has_sub_field('pricing')): ?>
            <div class="col-lg">
                <div class="col-lg">
                    <div class="pricing-widget <?php if (get_sub_field('highlight')): ?>main active<?php
                            endif; ?>">
                        <div class="pricing-header">
                            <div class="price-cost">
                                <div class="inner-price"><p class="inner-number"><?php the_sub_field('pricing'); ?></p></div>
                            </div>
                        </div>
                        <div class="pricing-content"><h3 class="pricing-title"><?php the_sub_field('title'); ?></h3>
                            <p class="pricing-subtitle"><?php the_sub_field('sub_content'); ?></p>
                            <?php
                            $options = get_sub_field('available_options');
                            $cls     = get_sub_field('title');
                            $arr     = explode(' ', trim($cls)); 
                            ?>

                            <ul class="pricing-list <?php echo strtolower($arr[0]); ?>">
                                <?php
                                /*availbale checkbox option display*/
                                $values  = get_sub_field('avail_opt5');
                                $field   = get_sub_field_object('avail_opt5');
                                $choices = $field['choices'];
                                $myarr   = array();
                                $newarr  = array();

                                foreach ($choices as $choice_value => $choice_label):
                                    $class = '';
                                    foreach ($values as $value):
                                        if ($value == $choice_value):
                                            $class = ' class="checked"';
                                            array_push($myarr, $choice_label);
                                        else:
                                            $class = '';
                                        endif;
                                    endforeach;

                                    if (!in_array($choice_value, $values)) {
                                        array_push($newarr, $choice_label);
                                    }
                                endforeach;

                                /*display available choice*/
                                foreach ($myarr as $val) {
                                    $journalName = preg_replace('/\s+/', '_', $val);
                                    echo '<li class="' . $journalName . '"><p><i class="fa fa-check-square-o"></i>' . __( $val, 'directorytheme' ) . '</p></li>';
                                }

                                foreach ($newarr as $val) {
                                    $journalName = preg_replace('/\s+/', '_', $val);
                                    $add_cs_class = ' class="noavail ' . $journalName . '"';
                                    echo '<li' . $add_cs_class . '><p><i class="fa fa-times-rectangle-o"></i>' . __( $val, 'directorytheme' ) . '</p></li>';
                                }

                                // check if the repeater field has rows of data
                                if (have_rows('custptionsrepeater')):
                                    // loop through the rows of data
                                    while (have_rows('custptionsrepeater')):
                                        the_row();
                                        $choice_switcher = get_sub_field('select_choice_switcher');
                                        $choice_name     = get_sub_field('avopn_choice');
                                        $journalName     = preg_replace('/\s+/', '_', $choice_name);
                                        if ($choice_switcher != 1) {
                                            $add_cs_class = ' class="noavail ' . $journalName . '"';
                                            echo '<li ' . $add_cs_class . '><p><i class="fa fa-times-rectangle-o"></i>' . __( $choice_name, 'directorytheme' ) . '</p></li>';
                                        } else {
                                            echo '<li class="' . $journalName . '"><p><i class="fa fa-check-square-o"></i>' . __( $choice_name, 'directorytheme' ) . '</p></li>';
                                        }
                                    endwhile;
                                else:
                                    // no rows found
                                endif; ?>

                            </ul>
                                <?php
                                $url    = get_site_url();
                                $txt    = get_sub_field('plan_url');
                                $parsed = parse_url($txt);

                                if ( isset($parsed['scheme']) ){
                                    if ( $parsed['scheme'] == 'https' or $parsed['scheme'] == 'http') { ?>
                                        <div class="pricing-button"><a href="<?php echo $txt ?>"><?php echo __('choose plan', 'directorytheme'); ?></a></div>
                                    <?php
                                    }
                                } else { ?>
                                    <div class="pricing-button"><a href="<?php echo get_home_url();
                                    echo "/";
                                    the_sub_field('plan_url'); ?>?listing_type=<?php echo sanitize_title(get_sub_field('title'));?>"><?php echo __('choose plan', 'directorytheme'); ?></a></div>
                                <?php
                                } ?>

                        </div>
                    </div>

                </div>
            </div>
                <?php
                endwhile;
            endif; ?>
        </div>

    </div><!--inner-->
</div><!--cp-container-->

<script>
jQuery('#cp-container img').addClass('img-fluid');
</script>
<?php get_footer();
