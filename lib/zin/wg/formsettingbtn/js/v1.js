function closeCustomPopupMenu(e) {
    $(e.target).closest('menu').removeClass('show');
}

function revertDefaultFields(e) {
    $.get($(e.target).closest('button').data('url'), function(data, status){
        if(status === 'success')
        {
            loadCurrentPage();
        }
    });

    return false;
}
