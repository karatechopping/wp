<?php
/**
 * The template for taxonomy pages
 */

get_header();

$clr_active = get_field('activate_custom_color', 'option');
$custom_clr = get_field('ppb_bg_color', 'option');
$icon_img   = '';
$bg_clr     = ($clr_active == 1 && !empty($custom_clr)) ? $custom_clr : '#000000';
$bg_img     = get_field('category_banner',get_queried_object());
$add_css    = '';
if(!empty($bg_img)){
    $add_css = 'background: url('.$bg_img.') no-repeat center center;background-attachment: fixed !important;background-size: cover !important;';
} ?>

<div id="page-banner" class="<?php echo $bg_clr; ?>" style="<?php echo $add_css; ?>">
  <div class="inner">
    <?php echo '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>'; ?>
  </div><!--inner-->
</div><!--page-banner-->

<div id="cp-container" class="cp-section single-listing-wrap">
    <div class="inner">
        <div id="listing_ajax" class="full-content">
            <?php
            the_archive_description( '<div class="taxonomy-description">', '</div>' );
            $content         = get_the_excerpt();
            $list_id         = get_the_ID();
            $post_img        = get_field('featured_image');
            $term            = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            $var             = print_r($term->slug, true);
            $cat_id          = print_r($term->term_id, true);
            $category        = get_queried_object();
            $post_ids1       = array();
            $list_id         = array();
            $pagination_flag = 1;
            $category->term_id;

            if ( is_numeric($post_img)) {
                $post_img = wp_get_attachment_url( $post_img );
            } else {
                $post_img = get_field( 'featured_image' );
            }

        if($category->term_id == $cat_id ){
            $posts = get_posts( array(
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_type'      => 'listings',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'listing-categories',
                        'field'    => 'term_id',
                        'terms'    => $cat_id
                    ),
                ),
                'orderby'    => 'meta_value_num',
                'meta_key'   => 'listing_order',
                'order'      => 'ASC',
                'meta_query' => array(
                    array(
                    'key'     => 'listing_order',
                    'value'   => '',
                    'compare' => '!='
                    ),
                )
            ) );

            $post_ids1 = array();
            if (!in_array($posts, $post_ids1)) {
                if(!empty($posts)) array_push($post_ids1,$posts);
            }

            /*2nd loop if order not given*/
            $posts = get_posts( array(
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_type'      => 'listings',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'listing-categories',
                        'field'    => 'term_id',
                        'terms'    => $cat_id
                    ),
                ),
                'orderby'    => 'title',
                'order'      => 'ASC',
                'meta_query' => array(
                    'relation'   => 'OR',
                    array(
                        'key' => 'listing_order',
                        'value' => '',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'listing_order',
                        'value' => '',
                        'compare' => '=='
                    ),
                )
            ) );

            $post_ids2 = array();
            if (!in_array($posts, $post_ids2)) {
                ( !empty($posts) ) ? array_push($post_ids2,$posts) : '';
            }

            if(sizeof($post_ids1) == 0){
                $arr = $post_ids2[0];
            }
            else if(sizeof($post_ids2) == 0){
                $arr = $post_ids1[0];
            }
            else{
                $arr = array_unique(array_merge($post_ids1[0],$post_ids2[0]), SORT_REGULAR);
            }

            $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
            $args = array (
                'post_type'      => 'listings',
                'post__in'       => $arr,
                'orderby'        => 'post__in',
                'posts_per_page' => '12',
                'paged'          => $paged,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'listing-categories',
                        'field'    => 'term_id',
                        'terms'    => $cat_id
                    ),
                ),
            );

    // The Query
    $cquery = new WP_Query( $args );
    if ( $cquery->have_posts() ) :

    while ( $cquery->have_posts() ) : $cquery->the_post();
        $list_id     = get_the_ID();
        $defualt_img = get_field('s_default_featured_image','option');
        $feature_img = get_the_post_thumbnail_url();
        $post_img    = get_field('featured_image');
        $sizeimg     = "img_1000x600"; // (thumbnail, medium, large, full or custom size)
        $side_image  = wp_get_attachment_image_src( $post_img, $sizeimg );
        $content     = get_the_excerpt();

        if (is_numeric($post_img)) {
            $post_img = wp_get_attachment_url($post_img);
        } else {
            $post_img = get_field('featured_image');
        }

        if( $list_id != '' ) { ?>
            <div class="col-lg-4">
                <div class="featured-listing">

                    <div class="fl-img">
                        <div class="zioea <?php echo get_field('listing_type');?>_5b28570780cc1">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php if($feature_img != ''){ echo $feature_img; } else if($post_img != ''){echo $post_img; } else if(!empty($defualt_img)){ echo $defualt_img; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; }?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"class="img-fluid" />
                            </a>
                        </div>
                        <div class="zioea <?php echo get_field('listing_type');?>_default_listing_image" style="display: none;">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php if(!empty($dtsimg)) { echo $dtsimg; } else { echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
                            </a>
                        </div>
                        <h3><a href="<?php the_permalink(); ?>" class="list-title-click"><?php the_title(); ?></a></h3>

                    <!-- for display other category--->

                    <div class="category_display">
                    <div class="row">

                    <?php
                    $terms    = get_field('category',$list_id);
                    $arr      = array();
                    $no_order = array();
                    if( $terms):

                        /*** Code for solving Multiple Category show on home page After Import tool ***/
                        if(!is_array($terms)){
                            $oigin_cat = get_the_terms( get_the_ID(),'listing-categories');
                            if(is_array($oigin_cat)){
                                if (array_key_exists(0,$oigin_cat)):
                                    $terms = wp_list_pluck( $oigin_cat, 'term_id' );
                                endif;
                            }
                        }
                        /*** End Code ***/ 

                        if(is_array($terms)):
                            ?>
                            <div class="col-md-12" style="padding : 0;">

                            <?php
                            if (array_key_exists("0",$terms)):
                                foreach( $terms as $term ):
                                    if(is_object($term)):
                                        $cat_term  = get_term( $term->term_id, 'listing-categories');
                                        $order_no  = get_field('category_order', $cat_term);
                                        $cat_index = $term->term_id;
                                        $terms     = term_exists( $cat_index, 'listing-categories' );
                                        if(!empty($terms)):
                                            if($order_no != ''){
                                                $arr[$cat_index] = $order_no;
                                            } else{
                                                array_push($no_order,$cat_index);
                                            }
                                        endif;
                                    else:
                                        $cat_term  = get_term( $term, 'listing-categories');
                                        $cat_index = $term;
                                        $terms     = term_exists( $cat_index, 'listing-categories' );
                                        if( !empty($terms) ):
                                            if( isset($order_no) && $order_no != ''){
                                                $arr[$cat_index] = $order_no;
                                            }
                                            else{
                                                array_push($no_order, $cat_index);
                                            }
                                        endif;
                                    endif;
                                endforeach;

                                if ( is_page('account') ) : echo "<div class='col-md-9'>";
                                else: echo "<div class='col-md-12'>"; endif;

                                    asort($arr);
                                    $merge_arr = array();
                                    foreach($arr as  $key=>$value){
                                        array_push($merge_arr,$key);
                                    }
                                    foreach($no_order as  $no_order_arr){
                                        array_push($merge_arr,$no_order_arr);
                                    }
                                    $term      = get_term($cat_id,'listing-categories');
                                    $cat_term  = get_term( $cat_id, 'listing-categories' );
                                    $icon_img  = '';
                                    if(get_field('icons_repeater',$cat_term)):
                                        $temp = 0;
                                        while(has_sub_field('icons_repeater',$cat_term)):
                                            if($temp == 0) $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                            $temp++;
                                        endwhile;
                                    endif;
                                    $term_link = get_term_link($term);
                                    echo "<div class='col-md-4'><a href='".$term_link."' class ='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                                    $j = 0;
                                    foreach ($merge_arr as $catnew){
                                        if( $j<5 ){
                                            $term      = get_term($catnew,'listing-categories');
                                            $term_link = get_term_link($term);
                                            if( $term->term_id != $cat_id ){
                                                $j++;
                                                $cat_term = get_term( $term->term_id, 'listing-categories' );
                                                $icon_img = '';
                                                if( get_field('icons_repeater',$cat_term) ):
                                                    $temp = 0;
                                                    while(has_sub_field('icons_repeater',$cat_term)):
                                                        if($temp == 0) $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                        $temp++;
                                                    endwhile;
                                                endif;
                                                echo "<div class='col-md-4'><a href='".$term_link."' class ='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                                            }
                                        } else { break; }
                                    }
                                    echo "</div>";
                                endif; ?>
                        </div>
                        <?php
                        else:
                            if($terms->name != '') {
                                $term = term_exists( $terms->slug, 'listing-categories' );
                                if ( $term !== 0 && $term !== null ):
                                    $cat_term = get_term($terms->term_id, 'listing-categories' );
                                    $term_link = get_term_link($cat_term);
                                    echo '<div class="col-md-12" style="margin-top: 10px;">';
                                    echo '<div class="col-md-4"><a href="#" class="nlplink"><span>'.$terms->name.'</span><a></div>';
                                    echo '</div>';
                                else :
                                    if($list_id != '') $terms1 = get_the_terms( $list_id, 'listing-categories' );
                                    if ( !empty($terms1)) {
                                        if(is_array($terms1)):
                                            echo '<div class="col-md-12" style="padding : 0;">';
                                            foreach($terms1 as $term):
                                                $term_link = get_term_link($term->term_id);
                                                $order_no  = get_field('category_order', $cat_term);
                                                $cat_index = $term->term_id;
                                                $terms     = term_exists( $cat_index, 'listing-categories' );
                                                if(!empty($terms)):
                                                    if($order_no != ''){
                                                        $arr[$cat_index] = $order_no;
                                                    }
                                                    else{
                                                        array_push($no_order,$cat_index);
                                                    }
                                                endif;
                                            endforeach;
                                            asort($arr);
                                            $merge_arr = array();
                                            foreach($arr as $key=>$value){
                                                array_push($merge_arr,$key);
                                            }
                                            foreach($no_order as $no_order_arr){
                                                array_push($merge_arr,$no_order_arr);
                                            }
                                            $term     = get_term($cat_id,'listing-categories');
                                            $term     = get_term($cat_id,'listing-categories');
                                            $cat_term = get_term( $term->term_id, 'listing-categories' );
                                            $icon_img = '';
                                            if(get_field('icons_repeater',$cat_term)):
                                                $temp = 0;
                                                while(has_sub_field('icons_repeater',$cat_term)): 
                                                    if($temp == 0) $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                    $temp++;
                                                endwhile;
                                            endif;

                                            $term_link = get_term_link($term);
                                            echo "<div class='col-md-4'><a href='".$term_link."' class ='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                                            $k = 0;
                                            foreach ($merge_arr as $catnew){
                                                if( $k<5 ){
                                                    $term      = get_term($catnew,'listing-categories');
                                                    $term_link = get_term_link($term);
                                                    if( $term->term_id != $cat_id ){
                                                        $k++;
                                                        $icon_img = '';
                                                        $cat_term = get_term( $term->term_id, 'listing-categories' );
                                                        if(get_field('icons_repeater',$cat_term)):
                                                            $temp = 0;
                                                            while(has_sub_field('icons_repeater',$cat_term)): 
                                                                if($temp == 0) $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                                $temp++;
                                                            endwhile;
                                                        endif;
                                                        echo "<div class='col-md-4'><a href='".$term_link."' class ='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                                                    }

                                                } else { break; }
                                            }
                                        else:
                                            $term_link = get_term_link($term1->term_id);
                                            $cat_term  = get_term( $term1->term_id, 'listing-categories' );
                                            $icon_img  = '';
                                            if( get_field('icons_repeater',$cat_term) ):
                                                $temp = 0;
                                                while(has_sub_field('icons_repeater',$cat_term)):
                                                    if($temp == 0) $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                    $temp++;
                                                endwhile;
                                            endif;
                                            echo "<div class='col-md-4'><a href='".$term_link."' class ='nlplink'><span>".$icon_img." ".$terms1->name."</span></a></div>";
                                        endif;
                                        echo '</div>';
                                    }
                                endif;
                            }// END if $terms->name
                        endif;
                    endif; ?>
                    </div>
                </div>
                <?php if(get_field('listing_type') != $choices_array1[0] ) : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/star.png" class="premium-star" />
                <?php endif; ?>
                <!-- icon setting--->
                    <?php
                    if(get_field('icon_repeater','option')): ?>
                        <div class="row">
                            <div class="col-md-12" style="padding : 0;">
                                <?php
                                if($list_id != '') $terms1 = get_the_terms( $list_id, 'listing-categories' );
                                if ( !empty($terms1)) {
                                    if(is_array($terms1)):
                                        echo '<div class="icons_section">';
                                        foreach($terms1 as $term):
                                            $cat_term = get_term( $term->term_id, 'listing-categories' );
                                            if(get_field('icons_repeater',$cat_term)):
                                                while(has_sub_field('icons_repeater',$cat_term)):
                                                    echo '<div class="col-md-2">';
                                                    $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                    $icon_link = get_sub_field('icons_cat_link',$cat_term);
                                                    $icon_img_color = get_sub_field('icons_cat_color',$cat_term);
                                                    $icon_bg_color = get_sub_field('icons_cat_bgcolor',$cat_term); ?>
                                                    <a <?php if($icon_link): ?> href="<?php echo $icon_link;?>" <?php endif; ?>  target="_blank" style ="color : <?php echo $icon_img_color; ?> ; background : <?php echo $icon_bg_color; ?>">
                                                    <?php echo $icon_img; ?></a>
                                                    <?php
                                                    echo '</div>';
                                                endwhile;
                                            endif;
                                        endforeach;
                                        echo '</div>';
                                    endif;
                                } ?>
                            </div>
                        </div>
                    <?php
                    endif; ?>
                    <!---  // for iocon setting-->
                    </div><!--- main div--->
                </div><!--blog-item-->
            </div><!--col-->
        <?php
        }
    endwhile;
endif;

wp_pagenavi( array( 'query' => $cquery ) );

} //END main if condition ?>
    </div>
  </div><!--inner-->
</div><!--cp-container-->
<?php
/*pricing page field selected by admin in backend Screenshort : https://prnt.sc/1ubl3uw */
        $page_ids = get_all_page_ids();
        global $pricing_page,$my_field;
        foreach($page_ids as $page){
            $pricing_page = get_the_title($page);
            if(get_page_template_slug($page) == "pricing.php"){  
                    $premium_field = array();
                    $pricing_page = $page ;
                    $pricing_cnt = get_post_meta($pricing_page,'pricing',true);
                    if(!empty($pricing_cnt)): 
                    $my_field = array();
                    for ($i=0; $i < $pricing_cnt; $i++) { 
                        $title_str = 'pricing_'.$i.'_title';
                        $price_title = get_post_meta($page,$title_str,true);   
                        $a = strtolower($price_title);
                        $choices_array[sanitize_title($a)] = $a;
                        $op_str = 'pricing_'.$i.'_avail_opt5';
                        $pay_method = get_post_meta($page,$op_str,true);
                        if(!empty($pay_method)):
                            foreach ($pay_method as $paymeth){
                                if($paymeth == 'business_description')  array_push($my_field,'5a5567c297a42');
                                if($paymeth == 'feature_img')  array_push($my_field,'5b28570780cc1');
                                if($paymeth == 'additional_detail')  array_push($my_field,'5a5567c297187');
                                if($paymeth == 'address') array_push($my_field,'5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map');   
                                if($paymeth == 'phone') array_push($my_field,'5a4df4y3er02w','5a2fb4f96ede0');  
                                if($paymeth == 'website') array_push($my_field,'5a2fb4ff6ede1');
                                if($paymeth == 'email_add') array_push($my_field,'5a0552cd48d5f','5a556a21dc86b');  
                                if($paymeth == 'cmp_logo') array_push($my_field,'5a2fb51a6ede2');
                                if($paymeth == 'schedules') array_push($my_field,'5a430c5235231');  
                                if($paymeth == 'video') array_push($my_field,'5a2fb52e6ede3');
                                if($paymeth == 'image_slideshow') array_push($my_field,'5a2fb53e6ede4');
                                if($paymeth == 'extra_links') array_push($my_field,'5aa8eb5906999');
                                if($paymeth == 'shortcode') array_push($my_field,'5aa8ec230plm4');
                                if($paymeth == 'social_media') array_push($my_field,'5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7');
                            }//foreach
                        endif;
                        $json_price_option[sanitize_title($a)]  = json_encode($my_field); 
                    }
                    endif;  
            } // if page is pricing
        }
        ?>
        <script type="text/javascript">
            /*pricing page option*/
            var price_option = [];
            <?php foreach($json_price_option as $key => $val): ?>
                    price_option['<?php echo $key; ?>'] = <?php echo $val; ?>;
            <?php endforeach; ?>
            jQuery.fn.myFunction = function(key) {
                var new_key = key; 
                var arrayFromPHP = price_option[key];
                var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4', '5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5a430c5235231','5b28570780cc1'];
                var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
                jQuery.each(difference1, function(index, value){
                    jQuery("."+ key + '_' +value).css({"display": "none"});
                    if(value == '5b28570780cc1'){
                        jQuery('.'+ key+'_default_listing_image').css({"display": "block"});
                    }
                });
                var difference = jQuery(exist_field).not(difference1).get();
                jQuery.each(difference, function(index, value){
                    jQuery("."+key+'_'+ value).css({"display": "block"});
                    if(value == '5b28570780cc1'){
                        jQuery('.'+ key+'_default_listing_image').css({"display": "none"});
                    }
                });
            }
            <?php foreach($choices_array1 as $key => $val): ?>
                <?php $listing_ty = $val; ?>
                var def_val = '<?php echo $listing_ty; ?>';
                jQuery.fn.myFunction(def_val);
            <?php endforeach; ?>
        </script>
<script>
  var divs = jQuery(".col-lg-4");
  for(var i = 0; i < divs.length; i+=3) {
    divs.slice(i, i+3).wrapAll("<div class='row'></div>");
  }
</script>

<?php get_footer();