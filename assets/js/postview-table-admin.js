jQuery(document).ready(function($) {

    jQuery('#postview-table-settings-form').submit(function(event) {
        var checkedCheckboxes = jQuery('input[name^="columns"]:checked');
        if (checkedCheckboxes.length === 0) {
            alert('Please select at least one column to display.');
            event.preventDefault(); 
        }
    });

});
