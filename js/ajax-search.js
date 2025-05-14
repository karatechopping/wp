jQuery(document).on('submit', '#search-container form', function() {
    var $form = jQuery(this);
    var $input = $form.find('input[name = "s"]');
    var query = $input.val();
    var $content = jQuery('# content')
    jQuery.ajax({
        type: 'post',
        url: myAjax.ajaxurl,
        data: {
            action: 'load_search_results',
            query: query
        },
        beforeSend: function() {
            $input.prop('disabled', true);
            $content.addClass('loading');
        },
        success: function(response) {
            $input.prop('disabled', false);
            $content.removeClass('loading');
            $content.html(response);
        }
    });
    return false;
})