$(function() {
    $('.admin-dropdownmenus-submit').on('click', function(e) {
        var dataAction = $(e.target).closest('input').attr('data-action');
        $('#acp_board').append('<input type="hidden" name="data-action" value="' + dataAction + '" />');
    });
    $('.admin-dropdownmenus-delete').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
    console.log('dropdownmenusadmin.js loaded');
});
