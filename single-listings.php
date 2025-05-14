<?php acf_form_head(); ?>
<?php
	/**
	 * The template for displaying all single listings
	 */

	get_header();
	global $choices_array1;
	$icon_img = '';
	$idx = 'gmaps_data_' . get_the_ID() . '_v1.16';
	$fetch_plugin_api_key = get_option( 'gpi_plugin_global_settings' );
	if(!empty($fetch_plugin_api_key )): 
		$plugin_api_key =  $fetch_plugin_api_key["api_key"];
	endif;
	if(!empty($plugin_api_key)){
		$api_key=$plugin_api_key;
	} else{
		$cmnapikey=get_field('googlemapcommonapikey','option');
		$api_key=$cmnapikey;
	}
	if ( false === ( $gapi_json = get_transient( $idx ) ) ) {
		$_plcid=get_post_meta(get_the_ID(), 'place_id', true);
		if (!empty($_plcid)) {
			$jsonlink="https://maps.googleapis.com/maps/api/place/details/json?place_id=".$_plcid."&key=".$api_key.'&fields=reviews';
			if(!empty($jsonlink)):
				$gapi_json = file_get_contents($jsonlink);
			endif;
			$gapi_json = json_decode($gapi_json, true);
			if (empty($gapi_json)): 
				$gapi_json = array('EMPTY'); 
			endif;
			if (!empty($gapi_json) && $gapi_json['status'] != 'OK'): 
				$gapi_json = array('EMPTY'); 
			endif;
			set_transient( $idx, json_encode($gapi_json), DAY_IN_SECONDS * 2 );
		}
	} else {
		$gapi_json = (array) json_decode($gapi_json, true);
	}
	if ($gapi_json == 'EMPTY'): 
		$gapi_json = ''; 
	endif;
	if( isset($gapi_json[0]) ){
		if($gapi_json[0] == 'EMPTY'){
			$gapi_json = '';
		}
	}
	if (isset($fetch_plugin_api_key["api_key_front_end"]) && !empty($fetch_plugin_api_key["api_key_front_end"])): 
		$plugin_api_key = $fetch_plugin_api_key["api_key_front_end"];
	endif;
	$cmnapikey_front_end=get_field('googlemapcommonapikey_frontend','option');
	if (!empty($cmnapikey_front_end)): 
		$cmnapikey = $cmnapikey_front_end; 
	endif;
	?>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php if(!empty($plugin_api_key)){ echo $plugin_api_key; } else { echo $cmnapikey; } ?>"></script>
	<?php 
		$featured_img = get_the_post_thumbnail_url();
		$post_img 	  = get_field('featured_image');
		if (is_numeric($post_img)) {
			$post_img = wp_get_attachment_url($post_img);
		} else {
			$post_img = get_field('featured_image');
		}
		$disable_banner = get_field('disable_banner','option');
		$listing_color  = get_field('listing_bnbg_color','option');
		$listing_bg_img = get_field('listing_bg_img','option');
		?>
		<!-- start to display single listing on page -->
		<div class="cp-header-title" <?php if($featured_img !="" && !get_field('disable_banner','option')){ ?> style="background: url('<?php echo $featured_img; ?>') no-repeat center center ;" <?php }else if($post_img !="" && !get_field('disable_banner','option')){ ?>style="background: url('<?php echo $post_img; ?>') no-repeat center center ;" <?php }else if ($listing_bg_img !="" && !get_field('disable_banner','option')) { ?> style="background: url(' <?php echo $listing_bg_img; ?>') no-repeat center center ;" <?php }else if($disable_banner == 1 && $listing_color != '') { ?> style="background-color : <?php echo $listing_color; ?>";<?php } ?> >
			<div class="inner">
				<h1 classs="listing-name" itemprop="name" <?php if(get_field('listing_type') != $choices_array1[0] ) : ?>class="premium_title"<?php endif; ?> >
					<?php 
						the_title(); 
						if(get_field('listing_type') != $choices_array1[0] ) : 
							?>
								<img src="<?php echo get_template_directory_uri(); ?>/images/star.png" class="premium-star" />
							<?php 
						endif; 
					?>
				</h1>
				<?php
				// vars
				$contact_address = get_field('address');
				$listing_ty      = get_field('listing_type');
				?>
				<!--Display address on banner -->
				<address  itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" class="listing-address-title">
					<?php 
						if(!empty($contact_address['address'])): 
							?><i class="fa fa-map-o"></i><?php 
							$address = explode( "," , $contact_address['address']);
							$tot = count($address);
							$new_arr = array_keys($address);
							$comma = end($new_arr);
							for($i=0;$i<$tot;$i++){
								echo $address[$i];
								if($i < $comma){
									echo ',';
								}
							}
						endif; 
					?>
				</address>
				<!-- Display Categories -->
				<div class="col-md-12 category_display" style="padding : 0;">
					<div class="row">
						<?php
							$terms = get_field('category');
							echo '<div class="show-menu-cat" style="display : none"><i class="fa fa-list" aria-hidden="true"></i> Category </div>';
							$arr = array();
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
									if (array_key_exists("0",$terms)):
										foreach($terms as $b){
											if(is_object($b)):
												$cat_term = get_term( $b->term_id, 'listing-categories' );
												$order_no = get_field('category_order', $cat_term);
												$cat_index = $b->term_id;
												$terms = term_exists( $cat_index, 'listing-categories' );
												if(!empty($terms)):
													if($order_no != ''){
														$arr[$cat_index] = $order_no;
													}else{
														array_push($no_order,$cat_index);
													}
												endif;
											else :
												$cat_term = get_term( $b, 'listing-categories' );
												$order_no = get_field('category_order', $cat_term);
												$cat_index = $b;	
												$terms = term_exists( $cat_index, 'listing-categories' );
												if(!empty($terms)):
													if($order_no != ''){
														$arr[$cat_index] = $order_no;
													}else{
														array_push($no_order,$cat_index);
													}
												endif;
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
										echo "<div class='col-md-6'>"; 
											$cat_no_cnt = 0;
											foreach ($merge_arr as $catnew) { 
												if($cat_no_cnt == 6):
													echo "<div class='caregory_wrap' style='display:none'>"; 
												endif;
												$cat_no_cnt++;
												$term = get_term($catnew);
												$term_link = get_term_link($term);
												$cat_term = get_term($term->term_id, 'listing-categories' );
												$icon_img = '';
												if(get_field('icons_repeater',$cat_term)):
													$temp = 0;
													while(has_sub_field('icons_repeater',$cat_term)): 
														if($temp == 0){
															$icon_img = get_sub_field('icons_cat_icon',$cat_term);
														}
														$temp++;
													endwhile;
												endif;
												echo "<div class='col-md-4'><a href='".$term_link."' ><span>".$icon_img." ".$term->name."</span></a></div>";
												if($cat_no_cnt == count($merge_arr)):
													echo "</div>"; 
												endif;
											}
											if(count($merge_arr) > 6){
												echo '<a class="moreless-button" href="javascript:void(0);"><span>More Categories</span></a>';
											}
										echo '</div>';
									endif; //key exits 
								else:
									if($terms->name != ''){
										$term = term_exists( $terms->slug, 'listing-categories' );
										if ( is_page('account') ) : 
											echo "<div class='col-md-9'>";
										else: 
											echo "<div class='col-md-6'>"; 
										endif;
										if ( $term !== 0 && $term !== null ):
											$cat_term = get_term($terms->term_id, 'listing-categories' );
											$term_link = get_term_link($cat_term);
											$icon_img = '';
											if(get_field('icons_repeater',$cat_term)):
												$temp = 0;
												while(has_sub_field('icons_repeater',$cat_term)):
													if($temp == 0){
														$icon_img = get_sub_field('icons_cat_icon',$cat_term);
													}
													$temp++;
												endwhile;
											endif;
											echo '<div class="col-md-4"><a href="'.$term_link.'"><span>'. $icon_img.' '.$terms->name.'</span><a></div>';
										else :
											$post_id = get_the_ID();
											if($post_id != ''){
												$terms1 = get_the_terms( $post_id, 'listing-categories' );
											}
											if ( !empty($terms1)) {
												if(is_array($terms1)):
													foreach($terms1 as $term){
														$cat_term = get_term($term->term_id, 'listing-categories' );
														$order_no = get_field('category_order', $cat_term);
														$cat_index = $term->term_id;
														$terms = term_exists( $cat_index, 'listing-categories' );
														if(!empty($terms)):
															if($order_no != ''){

															$arr[$cat_index] = $order_no;
															}
															else{
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
													foreach ($merge_arr as $catnew) { 
														$term = get_term($catnew);
														$term_link = get_term_link($term);
														$cat_term = get_term($term->term_id, 'listing-categories' );
														$icon_img = '';
														if(get_field('icons_repeater',$cat_term)):
															$temp = 0;
															while(has_sub_field('icons_repeater',$cat_term)): 
																if($temp == 0){
																	$icon_img = get_sub_field('icons_cat_icon',$cat_term);
																}
																$temp++;
															endwhile;
														endif;
														echo "<div class='col-md-4'><a href='".$term_link."' ><span>".$icon_img." ".$term->name."</span></a></div>";
													}
												else:
													$cat_term = get_term($term1->term_id, 'listing-categories' );
													$term_link = get_term_link($cat_term);
													$cat_term = get_term($term->term_id, 'listing-categories' );
													$icon_img = '';
													if(get_field('icons_repeater',$cat_term)):
														$temp = 0;
														while(has_sub_field('icons_repeater',$cat_term)): 
															if($temp == 0){
																$icon_img = get_sub_field('icons_cat_icon',$cat_term);
															}
															$temp++;
														endwhile;
													endif;
													echo "<div class='col-md-4'><a href='".$term_link."' ><span>".$icon_img." ".$terms1->name."</span></a></div>";
												endif;
											}
										endif;
										echo '</div>';
									}
								endif;
							endif;
						?>
					</div>
				</div> <!--catgoyy section -->
				<?php
					$reviews_num = get_comments_number();
					if($reviews_num!="0" && get_field('listing_type') != $choices_array1[0]) : 
						?>
						<div class="detail-overview-rating-title"></div>
						<?php
					endif; 
				?>
			</div><!--inner-->
		</div><!--cp-header-title-->
		<!-- Review section -->
		<div id="show_listing" class="cp-container">
			<div class="inner">
				<?php
					while ( have_posts() ) : 
						the_post();
						global $current_user,$csp,$plcid;
						$current_author = get_the_author_meta('ID');
						$json = $gapi_json;
						$googlecmnt = '';
						if($json){
							if(isset($json['result']['reviews'])){
								$googlecmnt=$json['result']['reviews'];
							}
						} 
						?>
						<div class="row">
							<div class="col-lg-5 order-lg-2">
								<div class="content-listing">
									<h3 itemprop="name" class="listing-title-sidebar">About<span><?php echo ' '.get_the_title(); ?></span>
										<?php  if ( is_user_logged_in() && $current_user->ID == $current_author  ) : ?>
											<button style="cursor: pointer; outline: none;display: none;" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_listing"><i class="fa fa-pencil"></i> <?php echo __('Edit', 'directorytheme'); ?></button>
											<a class='btn btn-sm btn-primary' href="<?php echo site_url(); ?>/listing_edit/?list_id=<?php echo get_the_ID(); ?>"><i class="fa fa-pencil"></i> <?php echo __('Edit', 'directorytheme'); ?></a>
										<?php endif; ?>
									</h3>
									<?php
									$csp=get_the_ID();
									$g_rating = get_post_meta($csp, 'g_rating', true);
									if(!empty($json)):
										if (array_key_exists("result",$json)):
										endif;
									endif;
									if($googlecmnt){
										if($g_rating > 1) {
											?>
											<div class="detail-overview-rating">
												<i class="fa fa-star"></i> 
												<strong><span class="cssureview"><?php echo $g_rating; ?></span> / 5 </strong><p class="review_from"><?php echo __('from reviews', 'directorytheme'); ?></p>
                							</div>
											<?php 
										} 
									}											
									/* sharing social icon*/
									$soc_icon = get_field('listing_soc_icon','option');
					    			if($soc_icon == 1):
					        			echo '<div class="col-md-6 social_icon">';
					        			$post_link = get_permalink();
					        			$title = get_the_title();
										echo '<a href="https://www.facebook.com/sharer?u='.$post_link.'&amp;t='.$title.'" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook-square"></i></a>';
										echo '<a title="Click to share this post on Twitter" href="http://twitter.com/intent/tweet?text=Currently reading '.$title.'&amp;url='.$post_link.'" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter-square"></i></a>';
										echo '<a href="http://pinterest.com/pin/create/button/?url='.$post_link.'&media='.$post_img.'&description='.$title.'" class="pin-it-button" count-layout="horizontal"><i class="fa fa-pinterest-square"></i></a>';
					       				echo '</div>';
					     			endif;
									/*claim listing start*/
									global $post_id,$alredy_cliam;
									$post_id = get_the_ID();
									$user_id = get_the_author_meta( 'ID' );
									$user = get_userdata($user_id);
									$enable_claim = get_field('claim_btn','option');
									if($enable_claim == 1):
										if(!empty( $user ) && $user){
										  $u = print_r($user->roles[0],true);
										}
										if($u == 'administrator'){
											$posts = get_posts( array(
												'posts_per_page' => -1,
												'post_type' => 'claim_listing',
												'meta_query' => array(
												'relation' => 'AND',
													array(
														'key' => 'claim_listingid',
														'value' => $post_id,
														'compare' => '=='
													),
													array(
														'key' => 'claim_status',
														'value' => 1,
														'compare' => '=='
													),
												)
											) );
											if(!empty($posts)): 
												$approve_id = $posts[0]->ID;
											else: 
												$alerady_claim = '';
											endif;
											if(empty($approve_id)){  //if lsiting approve to any user			
												?>
												<!-- Business description section -->
												<div class="welcome-btn">
													<p><?php echo __('Is this your business?', 'directorytheme'); ?><button id = 'claim' style="cursor: pointer; outline: none;" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#claim_listing"><?php echo __('Claim Listing', 'directorytheme'); ?></button></p>
												</div>
												<?php  // if alerady user claim this listing
											} else{
												echo '<h6 style="color : #4caf50;font-size:17px;"><i class="fa fa-check-circle" style="font-size:18px;"></i> Verified </h6>';
											}
										}
									endif; // enable_claim lsiting btn option
									?>
									<div class="listing-entry">
										<div class="row align-items-center listing-info">
					    					<?php 
												echo '<div class="5a2fb51a6ede2">';
												if(get_field('logo')): 
													?>
													<div class="col-lg-12">
														<img src="<?php the_field('logo'); ?>" class="img-fluid" />
													</div><!--col-->
													<?php 
												endif; 
												echo '</div>';
											?>
											<div class="col-lg-12">
												<?php 
													echo '<div class="5a4df4y3er02w">';
													$hpfsv=get_field('hide_phn_swtchr');
													if($hpfsv != '1') {
														if(get_field('phone')): 
															?>
															<div class="listing-info-item">
																<a itemprop="telephone" href="tel:<?php the_field('phone'); ?>"><i class="fa fa-phone"></i> <span><?php the_field('phone'); ?></span></a>
															</div><!--listing-info-item-->
															<?php 
														endif; 
													}
													echo '</div>';
												
													echo '<div itemprop="email" class="5a0552cd48d5f">';
													if(get_field('email_address')):
														$seap=get_field('show_email_public');
														if($seap != '1') :
							 								?>
															<div class="listing-info-item">
																<a itemprop="email" href="mailto:<?php the_field('email_address'); ?>"><i class="fa fa-envelope"></i> <span><?php the_field('email_address'); ?></span></a>
															</div><!--listing-info-item-->
															<?php 
														endif; 
													endif; 
													echo '</div>';

													echo '<div class="5a2fb4ff6ede1">';
													if(get_field('website')): 
														?>
														<div class="listing-info-item">
															<?php
																$website_url = get_field('website'); 
																if(strpos($website_url,'http') !== false){ 
																	?>
                                 									<a itemprop="url" href="<?php echo $website_url; ?>" target="_blank"><i class="fa fa-globe"></i> <span><?php echo __('Click to Visit Website', 'directorytheme'); ?></span></a>
                             										<?php   
                             									} else{ 
                             										?>
                                    								<a itemprop="url" href="http://<?php echo $website_url; ?>" target="_blank"><i class="fa fa-globe"></i> <span><?php echo __('Click to Visit Website', 'directorytheme'); ?></span></a>
                              										<?php  
                              									} 
                              								?>
														</div><!--listing-info-item-->
														<?php 
													endif; 
													echo '</div>'; 

													echo '<div class="5a430c5235231">';
													if( have_rows('schedules_days') ): 
														?>
														<div class="listing-info-item">
															<a href="#!" style="display: flex;pointer-events: none;"><i class="fa fa-clock-o"></i> 
																<table style="border-spacing: 6px;border-collapse: separate;">
																	<?php
																		while ( have_rows('schedules_days') ) : 
																			the_row();
																			?>
																			<tr>
																				<td><?php the_sub_field('single_schedule_day'); ?></td>
																				<td><?php echo '<b>:</b>'; ?></td>
																				<td>
																					<?php 
																						$dpoc=get_sub_field('openorclosed'); 
																						 if($dpoc == '1') {
																							the_sub_field('business_hour_from');
																							echo ' - ';
																							the_sub_field('business_hour_to');
																						} else {
																							echo "<b>Closed</b>";
																						}
																					?>
																				</td>
																			</tr>
																			<?php
																		endwhile; 
																	?>
																</table>
															</a>
														</div>
														<?php
													endif;
													echo get_field('single_schedule_day');
													echo '</div>';
												?>	
											</div><!--col-->
										</div><!--row-->
										<!-- user social sharing link--->
										<?php 
											echo '<div class="5ba9ec231plh8">';
											if(get_field('facebook_link') || get_field('twitter_link') || get_field('instagram_link') || get_field('linkedin_link')): 
												?>
												<div class="listing-info-item">
					    							<i class="fa fa-share-alt"></i>
				        							<div class="user_social_icon">
				            							<?php 
				            							if(get_field('facebook_link')) : echo '<a href="'.get_field("facebook_link").'" target="_blank"><i class="fa fa-facebook"></i></a>'; endif;
				                                        if(get_field('twitter_link')) : echo '<a href="'.get_field("twitter_link").'" target="_blank"><i class="fa fa-twitter"></i></a>'; endif;
				                                        if(get_field('instagram_link')) : echo '<a href="'.get_field("instagram_link").'" target="_blank"><i class="fa fa-instagram"></i></a>'; endif;
				                                        if(get_field('linkedin_link')) : echo '<a href="'.get_field("linkedin_link").'" target="_blank"><i class="fa fa-linkedin"></i></a>'; endif;
				                                        if(get_field('tiktok_link')) : echo '<a href="'.get_field("tiktok_link").'" target="_blank"><i class="fab fa-tiktok"></i></a>'; endif;
				                                        ?>
				        							</div>
												</div><!-- over user link--->
												<?php 
											endif; 
											echo '</div>';

											/*extra link*/
											echo '<div class="5aa8eb5906999">';
                    						if(get_field('extra_links') ): 
                								echo '<div class="all-buttons">';
                	 							while(has_sub_field('extra_links')): 
                        							$btn_text = get_sub_field("button_text");
                         							$btn_link= get_sub_field("button_link");
                        							$btn_target = (isset($btn_link['target']) && !empty($btn_link['target'])) ?  $btn_link['target'] : "_self";
                         							$btn_bg_color = get_sub_field("button_bg_color");
                         							$btn_txt_color = get_sub_field("btn_text_color");
                        							$btn_url = (isset($btn_link['url']) && !empty($btn_link['url'])) ?  $btn_link['url'] : "#";
                									echo '<div class="single-btn"><a href="'.$btn_url.'" target="'.$btn_target.'"><button style="border: none; border-radius: 5px; padding: 8px 18px; cursor: pointer; color:'.$btn_txt_color.'; background-color:'.$btn_bg_color.'">'.$btn_text.'</button></a></div>';
                	 							endwhile;
                								echo '</div>';
                 							endif; 
											echo '</div>';
											// icons setting start 
										?>
										<div class="listing_icon_all">
											<div class="row">
                								<div class="col-md-12">
            	    								<?php 
            	    									$post_id = get_the_ID();
                            							if($post_id != ''){
                                							$terms1 = get_the_terms( $post_id, 'listing-categories' );
                            							}
                         								if ( !empty($terms1)) {
                            								if(is_array($terms1)):
                                								echo '<div class="icons_section">';
                                								foreach($terms1 as $term):  
                                    								$cat_term = get_term( $term->term_id, 'listing-categories' );
                        											if(get_field('icons_repeater',$cat_term)):
                                	     								while(has_sub_field('icons_repeater',$cat_term)): 
                                	        								echo '<div class="single-btn">';
					                                                        $icon_img = get_sub_field('icons_cat_icon',$cat_term);
					                                                        $icon_link = get_sub_field('icons_cat_link',$cat_term);
					                                                        $icon_img_color = get_sub_field('icons_cat_color',$cat_term);
					                                                        $icon_bg_color = get_sub_field('icons_cat_bgcolor',$cat_term);
																			if(!empty($icon_img)):
                                	          									?>
                                	            								<a <?php if($icon_link): ?> href="<?php echo $icon_link;?>" <?php endif; ?>  target="_blank" style ="color : <?php echo $icon_img_color; ?> ; background : <?php echo $icon_bg_color; ?>; border: 1px solid #ddd;"><?php echo $icon_img; ?></a>
                                    		    								<?php
																			endif;
                                	         								echo '</div>';
                                	     								endwhile;
                                									endif;
                                								endforeach; 
                                								echo '</div>';
                            								endif;
                         								}
                    								?>
            									</div>
            								</div>
    									</div>
										<!-- custom field-->	 
                     					<div class="custom_field">
											<?php 
												$page_ids = get_all_page_ids();
												global $pricing_page,$my_field;
												echo '<ul style="list-style-type: none;">';
												foreach($page_ids as $page){
													$pricing_page = get_the_title($page);
													if($pricing_page == 'Pricing'){		
														$my_field = array();
														$premium_field = array();
														$pricing_page = $page ; 
														if(get_field('pricing',$pricing_page)):
															while(has_sub_field('pricing',$pricing_page)):
																if( strtolower(get_sub_field('title')) == $choices_array1[0]):
																	if(get_field('listing_type') == $choices_array1[0]):  
																		$rows = get_sub_field('custptionsrepeater',$pricing_page);
																	    if(!empty($rows)){
					    													foreach($rows as $newfi){
					    													    $i = 1;
					    														$custfi = $newfi['avopn_choice'];
					    														$str = strtolower($custfi);
					    														$str = preg_replace('/\\s/','',$str);
					    														$str = $str.$i.'_'.$choices_array1[0];
					    														$ty = $newfi['cust_type'];
					    														if($ty == 'ctm_img'):
					    															$image = get_field($str); 
				    																if(!empty($image)):
				    																 ?>
				    																 <li style="margin-bottom:10px;" class="<?php echo $str; ?>"><img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="300px" height="294px"/>
				    																</li>
				    														   		<?php  
				    														   		endif;
					    														else:
					    															$my_field = get_field($str);
					    															if(!empty($my_field)):
					    																echo '<li style="margin-bottom:15px;" class="'.$str.'">'.$my_field.'</li>';
					    															endif;
					    														endif;
					    													}
																	    } // if condition
																	endif; // free for listing type
																else:
																	//if(get_field('listing_type') != $choices_array1[0] ) :
																	if(sanitize_title(get_sub_field('title')) ==  get_field('listing_type')) :
																		$rows = get_sub_field('custptionsrepeater',$pricing_page);
																		if(!empty($rows)){
																			foreach($rows as $newfi){
																			    $i = 1;
																				$custfi = $newfi['avopn_choice'];
																				$str = strtolower($custfi);
																				$str = preg_replace('/\\s/','',$str);
																				//$str = $str.$i.'_'.$choices_array1[0]; 
																				$str = $str.$i;
																				$ty = $newfi['cust_type'];
																				if($ty == 'ctm_img'):

																						$image = get_field($str); 
																						if(!empty($image)):
																						 ?>
																						 <li style="margin-bottom:10px;" class="<?php echo $str; ?>"><img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="300px" height="294px"/>
																						</li>
																				   <?php
																						   endif;
																				else:
																					 $my_field = get_field($str);
																					if(!empty($my_field)):
																						echo '<li style="margin-bottom:15px;" class="'.$str.'">'.$my_field.'</li>';
																					endif;
																				endif;
																			}
																		} // if condition
																	endif; // premium for listing type 
																endif; // free  or premium for pricing page
															endwhile;
														endif;
													}
												}
											  	echo '</ul>';
											?>
										</div>	 <!-- custom field over--->
                     					<?php
                     						echo '<div class="5aa8ec230plm4">';
                     						/*shortcode*/
                     						if( have_rows('pre_shortcode') ):
                     							// loop through the rows of data
                        						while ( have_rows('pre_shortcode') ) : 
                        							the_row();
                            						// display a sub field value
                            						$get_shcode = get_sub_field('sub_shortcode');
                            						if(!empty($get_shcode)){
                                						echo '<div class="shortcode_section">';
                                						echo do_shortcode($get_shcode);
                                						echo '</div>';
                            						}
                        						endwhile;
                    						endif;
                    						/*shortcode over*/ 
                        					echo '</div>';

											echo '<div class="5a5567c297a42">';
											$listing_content = get_field('content');
											$additional_desc=get_field('additional_details');
											if($listing_content!=""): 
												?>
												<div class="listing-entry-content">
													<?php the_field('content'); ?>
												</div>
												<?php 
											endif; 
											echo '</div>';
										?>
									</div><!--listing-entry-->
								</div><!--content-listing-->
							</div><!--col-->
							<div class="col-lg-7">
								<?php
									if(!get_field('dreview', 'option')) : 
										if($g_rating > 1) {
											?>
											<div class="listing-comment">
												<div id="ggl-review">
													<div class="ggl-comment">
					    								<div class="review-owlcar">
															<?php
																if(!empty($googlecmnt)) {
																	foreach ($googlecmnt as $values) {
																		$starating=$values['rating'];
																		$date=date('F d, Y', $values['time']);
																		echo '<div class="ggl-rv-user-cmnt">';
																		echo '<div class="ggl-profile"><div class="ggl-profile-pic"><img src="'.$values['profile_photo_url'].'"></div><div class="gr-nd"><div class="ggl-name">'.$values['author_name'].'</div> <span class="dash">-</span> <div class="ggl-date">'.$date.'</div></div><div class="ggl-star-rate">';
																		for($i=1;$i<=5;$i++) {
																			if($i<=$starating) {
																				echo '<i class="fa fa-star"></i>';
																			}else {
																				echo '<i class="fa fa-star-o"></i>';
																			}
																		}
																		echo '</div></div>';
																		echo '<p class="ggl-rv-cmnt">'.$values['text'].'</p>';
																		echo '</div>';
																	}
																}
															?>
														</div>
													</div>
												</div>
											</div><!--listing-comment-->
											<?php 
										} 
									endif ; 
			
									echo '<div class="5a2fb52e6ede3">';
										if(get_field('video')): 
											?>
											<div class="listing-video">
												<div class="embed-container">
													<?php the_field('video'); ?>
												</div>
											</div><!--listing-video-->
											<?php 
										endif; 
									echo '</div>';

									echo '<div class="5b28570780cc1">';
				    					$dtsimg=get_field('s_default_featured_image','option');
										$ftdimg=get_the_post_thumbnail_url();
										$post_id = get_the_ID();
				    					?>
										<div class="listing-slide">
										    <img src="<?php if(!empty($post_img)) { echo $post_img; } elseif(!empty($ftdimg)) { echo $ftdimg; } else if(!empty($dtsimg)) { echo $dtsimg; } else{ echo bloginfo("template_url").'/images/Listing-Placeholder.png'; } ?>" alt="<?php the_title(); ?>" class="img-fluid" />
										</div>	 
										<?php
									echo '</div>';
			
									echo '<div class="5a2fb53e6ede4">';
										$images = get_field('images');
										$i = -1;
										echo '<div class="listing-slide">';
											if($images):
												?>
												<div id="cp-slide" class="carousel slide" data-ride="carousel">
													<?php 
														global $x;
            											$images_c = get_field('images');
            											if( $images_c ):
            												foreach( $images_c as $image_c ): 
            													$x++; 
            													?>
            			    									<div class="item <?php if($x==1):?>active<?php endif; ?>">
            		      	  										<img src="<?php echo $image_c['sizes']['img_1000x600']; ?>" class="img-fluid" />
            			    									</div><!--item-->
            													<?php 
            												endforeach; 
            											endif; 
            										?>
	  											</div><!--cp-slide-->
												<?php
											endif;
										echo '</div>';  //<!--listing-slide-->
									echo '</div>'; // main field if condition

									echo '<div class="5a4df4a8y3r17">';
										$hafpsv = get_field('hide_add_swtchr');
										$location = get_field('address');
										if($hafpsv != '1') {
											if( !empty($location['address']) ): 
												?>
												<div class="listing-address" itemscope itemtype="https://schema.org/LocalBusiness">
													<h3><?php echo __('Our Address', 'directorytheme'); ?></h3>
													<?php
														// vars
														$contact_address = get_field('address');
														if(!empty($contact_address['address'])): 
															?>
															<address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" class="listing-address-title"> <i class="fa fa-map-o"></i>
																<?php 
																	$address = explode( "," , $contact_address['address']);
						    										$tot = count($address);
						    										$new_arr = array_keys($address);
                        											$comma = end($new_arr);
                        											for($i=0;$i<$tot;$i++){
                        												echo $address[$i];
                        												if($i < $comma){
                        													echo ',';
                        												}
                        											}
																?>
															</address>
															<?php 
														endif; 
						    							$new_add = $location['address'];
						    							$result = str_replace(' ', '+', trim($new_add));
						    							$dierect_map = get_field('direction_on_map');
    						    						if($dierect_map == 1){ 
    						        						?>
    						      							<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $result; ?>" target="_blank" class="map_direction"><?php echo __('Get Directions', 'directorytheme'); ?></a>
                                    						<?php
    						    						}else { 
    						    							?>
							        		 				<div class="cp-map">
							        							<div class="acf-map">
							        								<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
							        							</div>
							        		  				</div><!--cp-map-->
        		  											<?php 
        		  										} 
        		   									?>
												</div><!--listing-address-->
												<?php 
											endif; 
										} 
										echo '</div>';
				
										echo '<div class="5a5567c297187">';
										if($additional_desc!=""):
											?>
											<div class="listing-entry-content">
												<?php echo get_field('additional_details'); ?>
											</div>
				 							<?php 
				 						endif;
			 						echo '</div>';
			 					?>
							</div><!--col-->
						</div><!---parent row-->
						<div class="row">
			              <div class="col-lg-5">
			                    <div class="ads_show">
			                        <div class="content-listing">
			                        <?php
			                        	$temp=get_field('hide_ads_free_listing', 'option');
			                        	if($temp == '1'){
			                        		echo '<div class="alladslisting">';
			                        		$ads_rotate =get_field('ads_rotate', 'option');
			                        		$ads_display = get_field('ads_display', 'option');
			                        		$fl = new WP_Query( array( 
												'post_type' => 'ads', 
												'posts_per_page' => '-1',
												'post_status' => 'publish'	,
												'orderby' => 'meta_value_num',
												'order' => 'ASC',
												'meta_query' => array(
													array(
														'key' => 'ads_order',
														'value' => '',
														'compare' => '!='
													)
												)
											) );
											global $post_type,$post_id;
											$ads_arr = array();
											$ads_display = array();
											$post_id = get_the_ID();
											$post_type = get_field('listing_type');
											$ads_display = get_field('ads_display', 'option');
											$post_objects = get_field('f_listing','option');
											if(!empty($post_objects)): 
												foreach ( $post_objects as $post ):
													$f_post = $post->ID;
													array_push($ads_arr,$f_post);
												endforeach;
											endif;
											$premium_ads = '';
											$free_ads = '';
											if($ads_display && in_array('premium_ads', $ads_display) ) :
												$premium_ads = 'premium_ads';
											endif;
											if(in_array('free_ads', $ads_display)) :
												$free_ads = 'free_ads';
											endif;
											global $featured_ads;
											$featured_ads = 0;
											if($ads_display && in_array('featured_ads', $ads_display)):
												if($ads_arr && in_array($post_id,$ads_arr)){
														$featured_ads = 1;
												}
											endif;
											
											if(($featured_ads == 1) || (!empty($premium_ads) && $post_type != $choices_array1[0]) || (!empty($free_ads) && $post_type == $choices_array1[0])): 
												?>
													<div class="<?php if($ads_rotate == 'rotate') echo 'owl-carousel owl-theme'; ?>">
														<?php
				                            			if ( $fl->have_posts() ) : 
				                            				while ( $fl->have_posts() ) : 
				                            					$fl->the_post(); 
				                            					$image = get_field('ads_image');
				                            					$size = 'img_1000x600'; // (thumbnail, medium, large, full or custom size)
																$d_img = get_field('s_default_featured_image','option');
																$expdate = get_field('exp_date');
																$today = new DateTime();
																$expdate= DateTime::createFromFormat('d/m/Y',$expdate);	
																$futureDate = new DateTime("today");
																$futureDate->modify("+2 days"); ?>
				                               					<div class="ads-listing <?php if($expdate){ $today=$today->format('Y-m-d'); $expdate=$expdate->format('Y-m-d'); if($today <= $expdate){ echo 'show-exp-ads'; }else if($today >= $expdate){ echo 'hide-exp-ads'; }else if($futureDate >= $today){ echo "FUTURE"; }else{ echo "show-exp-ads"; } } ?> ">
																	<div class="ads-img">
																		<?php 
																			if(get_field('ads_url') ) : 
																				if(wp_get_attachment_image( $image, $size )){ 
																					?>
																						<a href="<?php the_field('ads_url'); ?>" target="_blank" class="snip1535">
																							<?php 
																								echo wp_get_attachment_image( $image, $size ); 
																								$title = get_the_title();
																								if (!empty($title)) : 
																									?>
																										<h3><?php the_title(); ?></h3>
																									<?php 
																								endif; 
																							?>
																						</a>
																					<?php 
																				}else {
																					?>
																						<a href="<?php the_field('ads_url'); ?>" target="_blank" class="no_ad_img">
																							<?php the_title(); ?>
																						</a>
																					<?php 
																				}
																			else: 
																				echo '<div class="snip1535">'.wp_get_attachment_image( $image, $size ); 
																				$title = get_the_title();
																				if (!empty($title)) : 
																					?>
																						<h3><?php the_title(); ?></h3>
																					<?php 
																				endif; 
																				echo '</div>';
																			endif; 
																		?>
																	</div>
																	<?php 
																		if(get_field('ads_description')){ 
																			?>
																				<div class="ads_content">
																					<?php 
																						if(empty(wp_get_attachment_image( $image, $size )) && empty(get_field('ads_url'))) { 
																							?>
																								<div style="margin-top : 30px">
																									<?php the_field('ads_description');?>
																								</div>
																							<?php 
																						} else {  
																							the_field('ads_description'); 
																						}
																						if(get_field('ads_url') ) : 
																							?>
																								<a href="<?php the_field('ads_url'); ?>" target="_blank" class="global-btn btn-full">
																									<?php _e('Read More', 'directorytheme'); ?>
																								</a>
																							<?php 
																						endif; 
																					?>
																				</div>
																			<?php 
																		} 
																	?>
																</div>
																<?php 
															endwhile; 
															wp_reset_query(); 
														endif;
														?>
													</div> 
												<?php		
											endif; //ads_display or condition
											echo '</div>';
										}
									?>
									</div><!--content-listing-->
								</div> <!-- ads_show-->
			                </div> <!-- col-lg-5 -->
						</div><!--row---->
						<?php 
					endwhile; 
				?>
			</div><!-- #primary -->
		</div><!-- .cp-container -->
		<?php
			// featured listing section
			get_template_part( 'template-parts/dt/front', 'singlelisting-script' );
		?>
		<!-- Modal -->
		<div class="modal fade" id="claim_listing" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
						<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"><?php echo __('Claim Listing:', 'directorytheme'); ?>  <?php echo get_the_title($post_id); ?></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  							<span aria-hidden="true">&times;</span>
						</button>
						</div>
						<div class="modal-body">
  						<?php  
  							if ( !is_user_logged_in() ) : 
  								?>
								<h6 style="font-weight : 400;color : #000;"><?php echo __('Please Login or Register an Account to Claim Listing', 'directorytheme'); ?></h6>
								<a href="<?php echo get_permalink( get_page_by_path( 'account' ) ); ?>" class="global-btn  btn-solid" style="margin-top: 10px;"><?php echo __('Login', 'directorytheme'); ?></a> 		
  								<?php 
  							else :
  								$currnet_user_id = get_current_user_id();
								$posts1 = get_posts( array(
									'posts_per_page' => -1,
									'post_type' => 'claim_listing',  
									'meta_query' => array( 
										'relation' => 'AND',
										array(
											'key' => 'claim_listingid',
											'value' => $post_id,
											'compare' => '=='
										),   
										array(
											'key' => 'claim_userid',
											'value' => $currnet_user_id,
											'compare' => '=='
										),	
									)  
								) );
 								if(!empty($posts)): 
 									$approve_id = $posts1[0]->ID;
								else: 
									$alerady_claim = '';
								endif;
  								if(!empty($posts1) || !empty($alerady_claim)){
									echo '<h6 style="font-weight : 600;color : #000;">Sorry!</h6>';
									echo '<p>You have already submitted claim for this listing.</p>';
  								}else{
	  								echo '<div id="claim_div"></div>';
 								}
							endif; 
						?>
						</div>
						<div class="modal-footer">
  						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'directorytheme'); ?></button>
					</div>
				</div>
				</div>
		</div>
		<!-- cliam listing over-->
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
				                  	if($paymeth == 'social_media') array_push($my_field,'5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx');
		                		}//foreach
		              		endif;
		              		$json_price_option[sanitize_title($a)]  = json_encode($my_field); 
        				}
      				endif;  
    			} // if page is pricing
			}
		?>
		<style type="text/css">
			#ui-datepicker-div{
			    display: none !important;
			}
			.popover.clockpicker-popover {
		        z-index: 99999;
		    }
		    .owl-theme .owl-nav [class*=owl-]{background: #00000029 !important;}
			.owl-prev,.owl-next {padding : 0px 12px;font-size : 25px;border-radius:0px;}

			.owl-prev, .owl-next{
				display: block !important;
			}
			#cp-slide .owl-nav{
				top: 45%;
			}
			.review-owlcar{
				position: relative;
			}
			#cp-slide, .review-owlcar {
				width: 100%;
		    	height: auto;
			}
			#cp-slide .owl-stage-outer, .review-owlcar .owl-stage-outer{
		    	overflow: hidden;
			}	
			#cp-slide .owl-stage, .review-owlcar .owl-stage{
				display: flex;
			}
		</style>
		<script src="<?php echo get_template_directory_uri(); ?>/js/owl.carousel.min.js?<?php echo time(); ?>"></script>
		<script>
			jQuery('.owl-carousel').owlCarousel({
				autoplayTimeout: 2000,
				dots:false,
				stopOnHover : true,
				autoplayHoverPause: true,
				items: 1,
				loop: true,
				mouseDrag: false,
				touchDrag: false,
				pullDrag: false,
				rewind: true,
				autoplay: true,
				margin: 0,
				nav: true,
			});
			jQuery('#cp-slide').owlCarousel({
				autoplayTimeout: 3000,
				dots:false,
				items: 1,
				loop: true,
				mouseDrag: false,
				touchDrag: false,
				pullDrag: false,
				autoplay: true,
				nav: true,
			});
			jQuery('.review-owlcar').owlCarousel({
				autoplayTimeout: <?php echo (get_field('reviews_slide_speed', 'option') * 1000); ?>,
				dots:false,
				items: 1,
				loop: true,
				mouseDrag: false,
				touchDrag: false,
				pullDrag: false,
				autoplay: true,
				nav: true,
			});
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.0.0/jquery.datetimepicker.js<?php echo time(); ?>"></script> 
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css<?php echo time();?>">
		<script>
			const convertTime12to24 = (time12h) => {
				const time 	   = time12h.slice(0, 5);
				const modifier = time12h.slice(-2);
				let [hours, minutes] = time.split(':');

				if (hours === '12') {
					hours = '00';
				}
				if (modifier === 'PM') {
					hours = parseInt(hours, 10) + 12;
				}
			  	return `${hours}:${minutes}`;
			}

			jQuery(document).on('click','.acf-time-picker .input',function() {
				jQuery(".xdsoft_datetimepicker").css("width", jQuery(this).outerWidth()+"px");
				jQuery('.acf-time-picker .input').datetimepicker({
						datepicker:false,
						formatTime: 'h:i A',
						ampm: true, // FOR AM/PM FORMAT
						format : 'h:i A',
			            // step: 30, // For 30min interval
			             onSelectTime: function(ct, $input){

			               $input.prev("input").val(ct.dateFormat('H:i:s'));

			             },
			              onClose:function(dp,$input){
			                $input.prev("input").val(dp.dateFormat('H:i:s'));
			              },
			              forceParse: false,
			              showLeadingZero: false,
			              allowInputToggle:true,
			              validateOnBlur:false
			    }).on('keyup', function(){
			        jQuery('.xdsoft_datetimepicker').hide();
			        if(jQuery(this).val() != ''){
			            jQuery(this).prev("input").val(jQuery.trim(convertTime12to24(jQuery(this).val()))+":00");
			        }
			    });
			});
		 	jQuery(document).ready(function(){
		 	    jQuery('.show-menu-cat').css("display","none");
		 	    if (jQuery(window).width() <= 767) {
		            jQuery(".single-listings .row .col-md-6").hide();
					jQuery(".show-menu-cat").click(function(){
						jQuery(".single-listings .row .col-md-6").toggle();
		            });
		 	    }
				jQuery('#claim').click(function(){
		            var claim_id = '<?php echo $post_id; ?>';
					var alerady_claim = '<?php echo $alerady_claim; ?>';
					if(alerady_claim === ''){
						jQuery.ajax({
							url: "<?= admin_url('admin-ajax.php'); ?>", 
							type: 'post',
							data:{ 
								action :'claim_listing_user',
								claim_id: claim_id,
							},
							success:function(response) {
								document.getElementById('claim_div').innerHTML  = response;
							}
						});
					}
				});
			});
			jQuery('label[for="acf-_post_title"]').html("Name <span class='acf-required'>*</span>");
			jQuery(".submit-form").click(function(){ jQuery('#cliam_form').submit(); });
			jQuery(window).load(function(){
			    jQuery('.acf-field--post-title').hide();
			    if (jQuery(window).width() >= 767) { 
			 	    jQuery(".ads_show").appendTo(".col-lg-5.order-lg-2");
			 	}
			});
			function makeSingleCallFun1(fun) {
			  	var called = false;
			  	return function() {
			    	if (!called) {
			      		called = true;
			      		return fun.apply(this, arguments);
			    	}
			  	}
			}
			var submitSearchForm = makeSingleCallFun1(function() {
				var msg = 'Thank You!\n' +
			           'Your claim request has been submitted.' + 
					   'Please sit back and relax it may take up to few business days for approval.';
				alert(msg);
			});
			function makeSingleCallFun(fun) {
			  	var called = false;
			  	return function() {
			    	if (!called) {
			      		called = true;
			      		return fun.apply(this, arguments);
			    	}
			  	}
			}
			var myFun = makeSingleCallFun(function() {
				jQuery('#acf-form2').submit(function(e) {
					 submitSearchForm();
				});
			});
			if (window.history.replaceState ) {
			   window.history.replaceState( null, null, window.location.href );
			}

			/*pricing page option*/
			var price_option = [];
			<?php foreach($json_price_option as $key => $val): ?>
					price_option['<?php echo $key; ?>'] = <?php echo $val; ?>;
			<?php endforeach; ?>
			jQuery.fn.myFunction = function(x) {
				var arrayFromPHP = price_option[x];
				var exist_field = ['5a5567c297a42','5a5567c297187','5a4df4a8y3r17','5a2fb4cc6eddf','direction-on-map','5a4df4y3er02w','5a2fb4f96ede0','5a0552cd48d5f','5a556a21dc86b','5a2fb4ff6ede1','5a2fb51a6ede2','5a2fb52e6ede3','5a2fb53e6ede4', '5aa8eb5906999','5aa8ec230plm4','5ba9ec231plh8','5ba9fc231poh2','5ba3gc231pod4','5ba3gc234pjl7','5ba3gc23dfvx','5a430c5235231','5b28570780cc1'];
				var difference1 = jQuery(exist_field).not(arrayFromPHP).get();
				if(arrayFromPHP != '' ){
					jQuery.each(difference1, function(index, value){
						if(value == '5b28570780cc1'){
							jQuery('.cp-header-title').css({"background": "url('https://directorysite.sharksdemo.com/wp-content/themes/directorytheme/images/Listing-Placeholder.png') no-repeat center center"});
							jQuery('.5b28570780cc1 .listing-slide img').attr('src','https://directorysite.sharksdemo.com/wp-content/themes/directorytheme/images/Listing-Placeholder.png');
						}else{
							jQuery("."+ value).css({"display": "none"});
						}
					});
				}
				var difference = jQuery(exist_field).not(difference1).get();
				jQuery.each(difference, function(index, value){
					jQuery("."+ value).css({"display": "block"});
				});
			}
			var def_val = '<?php echo $listing_ty; ?>';
			jQuery.fn.myFunction(def_val);
			/* pricing page option over*/
		</script>
		<style>
			.field_show{display : block;}
			.field_hide{display : none;}

			.show-menu-cat {color : #fff;padding-left : 15px;}
			.my_close_btn {display: inline-block;
			    text-align: center;
			    width: 50px;
			    height: 50px;
			    float: right;
			    font-size: 30px;}
			@media (min-width: 768px) and (max-width: 1024px){
				.single-listings .row .col-md-6 {
					-ms-flex: 0 0 100%;
					flex: 0 0 100%;
					max-width: 100%;
				}
			}

			@media (min-width: 480px) and (max-width: 767px){
				.single-listings .row .col-md-6 .col-md-4 {
					float: left;
					width: 50%;
					padding: 0 5px;
					margin: 0px;
				}
			}
			@media (max-width: 768px){
				.show-menu-cat{
					display:block !important;
					margin-bottom : 10px;
				}
			}
		</style>
	<?php 
get_footer();