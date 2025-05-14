<?php    
    if ( is_user_logged_in() ) : ?>
<!-- Modal popup for edit listing -->
<div class="modal fade" id="edit_listing1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo __('Edit', 'directorytheme'); ?> <?php the_title(); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<?php 


$args = array(
          'post_id' => get_the_ID(),
          //'field_groups' => array('group_5a2df2c2e8d84'),
          'fields' => array('field_5a508d0511150',
          'field_5a5567c297a42',
          'field_5a5567c297187',
          'field_5a2fb51a6ede2',
          'field_5a430c5235231',
          //'field_5a4df4a8y3r17',
          //'direction_on_map',
          'field_5a2fb4cc6eddf',
          'field_5a4df4y3er02w',
          'field_5a2fb4f96ede0',
          'field_5a0552cd48d5f',
          'field_5a556a21dc86b',
          'field_5b28570780cc1', //feture_img
          'field_5a2fb4ff6ede1',
          'field_5a2fb52e6ede3',
          'field_5a2fb53e6ede4',
          'field_5a2fb5a20bb13',
          'field_5aa8eb5906999',
          'pre_shortcode',
          'facebook_link',
          'twitter_link',
          'instagram_link',
          'linkedin_link'
          ),
          'form' => true,
          'html_before_fields' => '',
          'html_after_fields' => '',
          'post_status'  => 'draft',
          'submit_value' => 'Update',
          'updated_message' => __('Update done.', 'directorytheme')
        );
acf_form( $args );

?>
<?php endif; ?>		  
      </div>
    </div>
  </div>
</div>
