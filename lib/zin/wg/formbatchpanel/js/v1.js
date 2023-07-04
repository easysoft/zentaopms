window.closeCustomPopupMenu = function(e) {
    $(e.target).closest('menu').removeClass('show');
}
