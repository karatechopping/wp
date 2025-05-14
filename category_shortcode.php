<?php
function category_shortcode() {
     /* Note :shorcode apply in front-listing.php,front-feature.php,listing.php,listing-search.php & account.php(footer.php)
     not apply short code in single-listing.php & taxonomy-listing-categories.php so make manually changes in both page*/

    $icon_img = ''; ?>
  
    <div class="row">
	    <?php  
        $terms    = get_field('category');
        $arr      = array();
        $no_order = array();
        if( $terms):
            /*** Solving Multiple Category show on home page After Import tool ***/ 
            if( !is_array($terms) ){
                $oigin_cat = get_the_terms( get_the_ID(),'listing-categories'); 
                if ( is_array($oigin_cat) && array_key_exists(0, $oigin_cat) ) {  
                    $terms = wp_list_pluck($oigin_cat, 'term_id');
                }
            }
                    
                if(is_array($terms)):
                    if(array_key_exists("0",$terms)):
                        foreach($terms as $b){
                            if(is_object($b)):
                                $cat_term  = get_term( $b->term_id, 'listing-categories' );
                            	$order_no  = get_field('category_order', $cat_term);	
                            	$cat_index = $b->term_id;
                            	$terms     = term_exists( $cat_index, 'listing-categories' );
                                if( !empty($terms) ):
                                    if($order_no != ''){
                                	    $arr[$cat_index] = $order_no;
                                	}else{
                                        array_push($no_order,$cat_index);
                                	}
                            	endif;
                            else:
                                $term       = get_term( $b, 'listing-categories');
                                $order_no   = get_field('category_order', $term);	
                                $cat_index  = $b;
                                $terms      = term_exists( $cat_index, 'listing-categories' );
                                if( !empty($terms) ):
                                    if($order_no != ''){
                                        $arr[$cat_index] = $order_no;
                                	}else{
                                        array_push($no_order,$cat_index);
                                	}
                            	endif;
                            endif;
                        }

                        asort($arr);
                        $merge_arr = array_merge(array_keys($arr), $no_order);
                        
                        //$merge_arr = array();
                        // foreach($arr as  $key=>$value){
                        //     array_push($merge_arr,$key);
                        // }
                        // foreach($no_order as $no_order_arr){
                        //     array_push($merge_arr, $no_order_arr);
                        // }
                        
                        $newArr = array_splice($merge_arr, 0, 6);
                        $cols   = array_chunk($newArr, 3);
                        
                        foreach($cols as $catnm){
                            if ( is_page('account') ) : 
                                echo "<div class='col-md-9'>";
                            else: 
                                echo "<div class='col-md-12'>"; 
                            endif;
                            foreach($catnm as $catnew) { 
                                $term       = get_term($catnew);
                                $term_link  = get_term_link($term);
                                $cat_term   = get_term( $term->term_id, 'listing-categories' );
                                $icon_img   = '';
                                if( get_field('icons_repeater',$cat_term) ):
                                    $temp = 0;
                                    while(has_sub_field('icons_repeater',$cat_term)): 
                                        if($temp == 0){
                                            $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                        }
                                    	$temp++;
                                    endwhile;
                                endif;
                                echo "<div class='col-6 col-md-4'><a href='".$term_link."' class='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                            }
                            echo "</div>";
                        }
                    endif; //key exits 
                else:
                    if($terms->name !== ''){
                        $term = term_exists( $terms->slug, 'listing-categories' );
                        
                        if ( is_page('account') ) : 
                            echo "<div class='col-md-9'>";
                        else: 
                            echo "<div class='col-md-12'>"; 
                        endif;
                        
                        if ( $term !== 0 && $term !== null ):
                            $cat_term = get_term($terms->term_id, 'listing-categories' );
                            if(!empty($cat_term)):
                                $term_link = get_term_link($cat_term);
                            else:
                                $term_link = '';    
                            endif;
                            echo '<div class="col-6 col-md-4"><a href="'.$term_link.'" class="nlplink"><span>'.$terms->name.'</span><a></div>';
                        else :
                            $post_id = get_the_ID();
                            if($post_id != ''){
                                $terms1 = get_the_terms( $post_id, 'listing-categories' );
                            }
                            
                            if ( !empty($terms1)) {
                                if(is_array($terms1)):
                                    foreach($terms1 as $term){
                                        $cat_term  = get_term($term->term_id, 'listing-categories' );
                                        $order_no  = get_field('category_order', $cat_term);
                            			$cat_index = $term->term_id;
                                		$terms     = term_exists( $cat_index, 'listing-categories' );
                                        if(!empty($terms)):
                                            if($order_no != ''){
                                				$arr[$cat_index] = $order_no;
                                    		}else{
                                    			array_push($no_order,$cat_index);
                                    		}
                                		endif;
                                    }
                                    asort($arr);
                                    $merge_arr = array();
                                    foreach($arr as  $key=>$value){
                                        array_push($merge_arr,$key);
                                    }
                                    foreach($no_order as  $no_order_arr){
                                        array_push($merge_arr,$no_order_arr);
                                    }
                                    
                                    $newArr = array_splice($merge_arr, 0, 6);
                                    $cols   = array_chunk($newArr,3);
                                    foreach ($cols as $catnm){
                                        if ( is_page('account') ) : 
                                            echo "<div class='col-md-9'>";
                                        else: 
                                            echo "<div class='col-md-12'>"; 
                                        endif;
                                        foreach($catnm as $catnew) { 
                                            $term      = get_term($catnew);
                                            $term_link = get_term_link($term);
                                            $cat_term  = get_term( $term->term_id, 'listing-categories' );
                                            $icon_img  = '';
                                            
                                            if(get_field('icons_repeater',$cat_term)):
                                                $temp = 0;
                                                while(has_sub_field('icons_repeater',$cat_term)): 
                                                    if($temp == 0){
                                                        $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                                    }
                                                	$temp++;
                                                endwhile;
                                            endif;
                                            echo "<div class='col-6 col-md-4'><a href='".$term_link."' class='nlplink'><span>".$icon_img." ".$term->name."</span></a></div>";
                                        }
                                        echo "</div>";
                                    }
                                else:
                                    $cat_term  = get_term($term1->term_id, 'listing-categories' );
                                    $term_link = get_term_link($cat_term);
                                    $icon_img  = '';
                                    if(get_field('icons_repeater',$cat_term)):
                                        $temp = 0;
                                        while(has_sub_field('icons_repeater',$cat_term)): 
                                            if($temp == 0){
                                                $icon_img = get_sub_field('icons_cat_icon',$cat_term);
                                            }
                                            $temp++;
                                        endwhile;
                                    endif;
                                    echo "<div class='col-6 col-md-4'><a href='".$term_link."' class='nlplink'><span>".$icon_img." ".$terms1->name."</span></a></div>";
                                endif;
                            }
                        endif;
                        echo '</div>';
                    }
                endif;
            endif; ?>
    </div>
<?php
}

add_shortcode('displ_cat', 'category_shortcode');