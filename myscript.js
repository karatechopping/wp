jQuery(document).ready(function(){
     
    //jQuery(document).on("click", ".is-dismissible .notice-dismiss", function () 
    jQuery('.is-dismissible .notice-dismiss').click(function()
            {
           
              var a = 1;            
			  
              jQuery.ajax(
                {               
                url: ajaxurl,
                type: 'post',
                data:{ 
                    action :'notification_msg',
                    close_btn: a                   
                },
                success:function(response)
                {
                  //alert(response);
                }
                });
            });
            
     jQuery(document).on("change", "select.fa-select2-field", function () {
         var selectedCountry = jQuery(this).children("option:selected").val();
       
        jQuery('.fa-live-preview').html('<i class="fa ' + selectedCountry + '"></i>' );
    });
});


