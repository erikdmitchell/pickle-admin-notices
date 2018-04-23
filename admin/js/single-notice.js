jQuery(document).ready(function($) {
   
    $('.pickle-admin-notices-admin .notice-input-row .notice-name').on('change', function() {
        $('.pickle-admin-notices-admin .notice-input-row .notice-slug').val($(this).val().replace(/ /g, '-').toLowerCase());
    });
    
});