jQuery(document).ready(function($) {
    
    $('#search-posts').on('input', function() {
        var searchTerm = $(this).val();
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data: {
                action: 'filter_posts',
                search: searchTerm
            },
            success: function(response) {
                var jsonObject = JSON.parse(response);
                $('.postview-table tbody').html(jsonObject.data);
            }
        });
    });
});
