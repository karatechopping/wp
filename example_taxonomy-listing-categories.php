<?php get_header(); ?>
    <?php 
        // Grab the queried taxonomy object & fetch its term_id
        $term_id = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $term_id = $term_id->term_id;
        // Prepare listings object matching the taxonomy term_id
        $listings = new WP_Query( array (
            'post_type' => 'listings',
            'status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => array (
                array(
                    'taxonomy' => 'listing-categories',
                    'field' => 'term_id',
                    'terms' => $term_id
                )
            )
        ) );
    ?>
    <?php $row = 0; ?>
    <?php $post_cats = []; ?>
    <?php
        function listing_categories_sorter( $listing_categories) {
            // First let's sort the array of objects in ascending order
            for($i = 0; $i < count($listing_categories); $i++) {
                for($j = $i; $j < count($listing_categories); $j++) {
                    if( round( (int) get_field( 'category_order', $listing_categories[$j] ) ) <= round( (int) get_field( 'category_order', $listing_categories[$i] ) ) ) {
                        $temp = $listing_categories[$i];
                        $listing_categories[$i] = $listing_categories[$j];
                        $listing_categories[$j] = $temp;
                    }
                }
            }
            // Now let's move all empty elements on the end of the array
            for($i = 0; $i < count($listing_categories); $i++) {
                if(get_field('category_order', $listing_categories[$i]) == '') {
                    $temp = $listing_categories[$i];
                    unset($listing_categories[$i]);
                    array_push($listing_categories, $temp);
                }
            }
            return $listing_categories;
        }
    ?>
    <!-- Hero Section -->
    <div id="page-banner" class="<?php echo $bg_clr; ?>">
        <div class="inner">
            <h1 class="page-title"><?php echo single_cat_title( '', false ); ?></h1>
            <?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
        </div><!--inner-->
    </div><!--page-banner-->
    <div id="cp-container" class="cp-section single-listing-wrap">
        <div class="inner">
            <div id="listing_ajax" class="full-content">
                <div class="row">
                <!-- Loop through the listings -->
                <?php while( $listings->have_posts() ) : $listings->the_post(); ?>
                    <!-- Grab all the terms for each listing from the "listing-categories" taxonomy   -->
                    <?php $post_taxonomy_terms = get_the_terms( get_the_ID(), 'listing-categories' ); ?>
                    <?php $column = 0; ?>
                        <div class="col-lg-4">
                            <div class="featured-listing">
                                <div class="fl-img">
                                    <div class="zioea">
                                        <a href="<?php the_permalink(); ?>"><img src="<?php if($post_img != ''){echo $post_img; } else if($feature_img != ''){ echo $feature_img; }else if(!empty($defualt_img)){ echo $defualt_img; } else{ echo bloginfo("template_url").'/images/Listing-Placeholder.png'; }?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="img-fluid" /></a>
                                    </div>
                                    <h3><a href="<?php the_permalink(); ?>" class="list-title-click"><?php the_title(); ?></a></h3>
                                    <div class="category_display">
                                        <div class="row">
                                            <div class="col-md-12" style="padding : 0;">
                                            <!-- Loop through the single listing's categories & combine them in a matrix -->
                                            <?php foreach( $post_taxonomy_terms as $term ) : ?>
                                                    <?php $get_field_term_id = "listing-categories_$term->term_id"; ?>  
                                                    <?php $post_cats[$row][$column] = $term; ?>
                                                    <?php $column++; ?>
                                            <?php endforeach; ?>
                                            <?php $sorted_categories = listing_categories_sorter($post_cats[$row]); ?>
                                            <?php foreach($sorted_categories as $category) : ?>
                                                <div class="col-md-4">
                                                    <a href="<?php echo get_term_link($category->term_id, 'listing-categories') ?>" class ='nlplink'><?php echo $category->name ?></a>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $row++; ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>