window.onChangeUser = function(event)
{
    const picker = $(event.target).zui('picker');
    const user   = picker ? picker.$.value : '';
    const type   = user ? 'account' : 'today';
    loadPage($.createLink('product', 'dynamic', `productID=${productID}&type=${type}&param=${user}`));
};

$(document).off('click', '.dynamic-collapse-icon').on('click', '.dynamic-collapse-icon', function(event){
    $(event.target).parent().parent().parent().toggleClass('collapsed');
});
